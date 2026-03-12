<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use App\Models\PlatformProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReelController extends Controller
{

    public function index(Request $request)
    {

        $query = Reel::with(['platformProduct.product']);

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Sorting
        if ($request->sort == 'views') {
            $query->orderBy('views_count', 'desc');
        } elseif ($request->sort == 'likes') {
            $query->orderBy('likes_count', 'desc');
        } else {
            $query->latest();
        }

        $reels = $query->paginate(10);

        // Stats for dashboard cards
        $stats = [
            'total_reels' => Reel::count(),
            'total_views' => Reel::sum('views_count'),
            'total_likes' => Reel::sum('likes_count'),
            'total_shares' => Reel::sum('shares_count'),
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
                'video' => 'required|file|mimes:mp4,mov,avi|max:20480',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string'
            ]);

            $videoPath = null;

            if ($request->hasFile('video')) {

                $file = $request->file('video');

                $videoPath = Storage::disk('s3')->putFileAs(
                    'admin/reels',
                    $file,
                    time() . '_' . $file->getClientOriginalName()
                );
            }

            Reel::create([
                'platform_product_id' => $request->platform_product_id,
                'title' => $request->title,
                'description' => $request->description,
                'video' => $videoPath,
                'status' => $request->status ?? 1
            ]);

            return redirect()
                ->route('admin.reels.index')
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
                'description' => 'nullable|string'
            ]);

            if ($request->hasFile('video')) {

                $file = $request->file('video');

                if ($reel->video && Storage::disk('s3')->exists($reel->video)) {
                    Storage::disk('s3')->delete($reel->video);
                }

                $videoPath = Storage::disk('s3')->putFileAs(
                    'admin/reels',
                    $file,
                    time() . '_' . $file->getClientOriginalName()
                );

                $reel->video = $videoPath;
            }

            $reel->update([
                'platform_product_id' => $request->platform_product_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status ?? 1,
                'video' => $reel->video
            ]);

            return redirect()
                ->route('admin.reels.index')
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

            if ($reel->video && Storage::disk('s3')->exists($reel->video)) {
                Storage::disk('s3')->delete($reel->video);
            }

            $reel->delete();

            return redirect()
                ->route('admin.reels.index')
                ->with('success', 'Reel deleted successfully');

        } catch (\Exception $e) {

            Log::error('Reel Delete Error', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Stats API (for modal)
     */
    public function stats($id)
    {
        $reel = Reel::findOrFail($id);

        return response()->json([
            'views_count' => $reel->views_count,
            'likes_count' => $reel->likes_count,
            'shares_count' => $reel->shares_count,
            'comments_count' => 0,
            'engagement_rate' => 0
        ]);
    }

}