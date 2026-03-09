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

    public function index()
    {
        $reels = Reel::with(['platformProduct.product'])
            ->latest()
            ->get();

        return view('admin.reels.index', compact('reels'));
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
                    time().'_'.$file->getClientOriginalName()
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
                'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480'
            ]);

            if ($request->hasFile('video')) {

                $file = $request->file('video');

                // delete old video from S3
                if ($reel->video && Storage::disk('s3')->exists($reel->video)) {
                    Storage::disk('s3')->delete($reel->video);
                }

                $videoPath = Storage::disk('s3')->putFileAs(
                    'admin/reels',
                    $file,
                    time().'_'.$file->getClientOriginalName()
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

}
