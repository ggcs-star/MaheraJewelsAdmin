<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|digits:10|regex:/^[0-9]{10}$/',
            'address'=> 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_profile_image' => 'nullable|boolean',
        ]);

        if ($request->has('remove_profile_image') && $request->remove_profile_image == '1') {
            if ($user->profile_image) {
                $oldPath = $user->getOriginal('profile_image');
                if ($oldPath && Storage::disk('s3')->exists($oldPath)) {
                    Storage::disk('s3')->delete($oldPath);
                }
            }
            $user->profile_image = null;
        }
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                $oldPath = $user->getOriginal('profile_image');
                if ($oldPath && Storage::disk('s3')->exists($oldPath)) {
                    Storage::disk('s3')->delete($oldPath);
                }
            }
            $path = $request->file('profile_image')->store('profile-images', 's3');
            $user->profile_image = $path; 
        }

        $user->name    = $request->name;
        $user->mobile  = $request->mobile;
        $user->address = $request->address ?? '';
        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }
}