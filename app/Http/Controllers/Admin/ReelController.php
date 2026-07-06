<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use App\Models\PlatformProduct;
use App\Models\ReelComment;
use App\Models\ReelShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Helpers\S3Helper;

class ReelController extends Controller
{
    public function index(Request $request)
    {
        $query = Reel::with(['platformProduct.product']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->sort == 'views') {
            $query->orderByDesc('views_count');
        } elseif ($request->sort == 'likes') {
            $query->orderByDesc('likes_count');
        } elseif ($request->sort == 'sort_order') {
            $query->orderBy('sort_order', 'asc');
        } else {
            $query->latest();
        }

        $reels = $query->paginate(10);

        $stats = [
            'total_reels' => Reel::count(),
            'total_views' => Reel::sum('views_count'),
            'total_likes' => Reel::sum('likes_count'),
            'total_shares' => Reel::sum('shares_count'),
            'total_comments' => Reel::sum('comments_count'),
        ];

        return view('admin.reels.index', compact('reels', 'stats'));
    }

    public function create()
    {
        $products = PlatformProduct::with('product')
            ->get()
            ->pluck('product.name', 'id');

        return view('admin.reels.create', compact('products'));
    }

    public function store(Request $request)
    {
        
        try {
            $request->validate([
                'platform_product_id' => 'required|exists:platform_products,id',
                'video' => 'required|file|mimes:mp4,mov,avi|max:102400',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'sort_order' => 'nullable|integer'
            ]);

            $videoPath = null;

            if ($request->hasFile('video')) {
                $file = $request->file('video');
                $name = $request->title
                    ? \Illuminate\Support\Str::slug($request->title)
                    : 'reel-' . time();

                $videoPath = S3Helper::storeAs(
                    $file,
                    'admin/reels',
                    $name . '.' . $file->getClientOriginalExtension()
                );
            }

            Reel::create([
                'platform_product_id' => $request->platform_product_id,
                'title' => $request->title,
                'description' => $request->description,
                'video' => $videoPath,
                'status' => $request->status ?? 1,
                'sort_order' => $request->sort_order ?? 0,
                'views_count' => 0,
                'likes_count' => 0,
                'shares_count' => 0,
                'comments_count' => 0,
            ]);

            return redirect()->route('admin.reels.index')
                ->with('success', 'Reel uploaded successfully');

        } catch (\Throwable $e) {
            Log::error('Reel Store Error', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $reel = Reel::findOrFail($id);
        $products = PlatformProduct::with('product')
            ->get()
            ->pluck('product.name', 'id');

        return view('admin.reels.edit', compact('reel', 'products'));
    }

    public function update(Request $request, $id)
    {
        try {
            $reel = Reel::findOrFail($id);

            $request->validate([
                'platform_product_id' => 'required|exists:platform_products,id',
                'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'sort_order' => 'nullable|integer'
            ]);

            if ($request->hasFile('video')) {
                $file = $request->file('video');

                if ($reel->video) {
                    S3Helper::delete($reel->video);
                }

                $name = $request->title
                    ? \Illuminate\Support\Str::slug($request->title)
                    : 'reel-' . $reel->id;

                $videoPath = S3Helper::storeAs(
                    $file,
                    'admin/reels',
                    $name . '.' . $file->getClientOriginalExtension()
                );

                $reel->video = $videoPath;
            }

            $reel->update([
                'platform_product_id' => $request->platform_product_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status ?? 1,
                'sort_order' => $request->sort_order ?? 0,
                'video' => $reel->video
            ]);

            return redirect()->route('admin.reels.index')
                ->with('success', 'Reel updated successfully');

        } catch (\Exception $e) {
            Log::error('Reel Update Error', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $reel = Reel::findOrFail($id);

            if ($reel->video) {
                S3Helper::delete($reel->video);
            }

            $reel->delete();

            return redirect()->route('admin.reels.index')
                ->with('success', 'Reel deleted successfully');

        } catch (\Exception $e) {
            Log::error('Reel Delete Error', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function addComment(Request $request, $reelId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        ReelComment::create([
            'reel_id' => $reelId,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        Reel::where('id', $reelId)->increment('comments_count');

        return back()->with('success', 'Comment added');
    }

    public function deleteComment($id)
    {
        $comment = ReelComment::findOrFail($id);
        Reel::where('id', $comment->reel_id)->decrement('comments_count');
        $comment->delete();

        return back()->with('success', 'Comment deleted');
    }

    public function addShare($reelId)
    {
        ReelShare::create([
            'reel_id' => $reelId,
            'user_id' => auth()->id(),
            'platform' => 'admin'
        ]);

        Reel::where('id', $reelId)->increment('shares_count');

        return back()->with('success', 'Share added');
    }

    public function stats($id)
    {
        $reel = Reel::findOrFail($id);
        $engagement = 0;

        if ($reel->views_count > 0) {
            $engagement = (
                ($reel->likes_count + $reel->comments_count + $reel->shares_count)
                / $reel->views_count
            ) * 100;
        }

        return response()->json([
            'views_count' => $reel->views_count,
            'likes_count' => $reel->likes_count,
            'shares_count' => $reel->shares_count,
            'comments_count' => $reel->comments_count,
            'engagement_rate' => round($engagement, 2)
        ]);
    }
}