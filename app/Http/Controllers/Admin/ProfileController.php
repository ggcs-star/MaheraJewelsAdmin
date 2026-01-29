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
        ], [
            'name.required' => 'Name field is required',
            'mobile.required' => 'Mobile number is required',
            'mobile.digits' => 'Mobile number must be exactly 10 digits',
            'mobile.regex' => 'Mobile number must contain only numbers',
            'profile_image.image' => 'Please upload a valid image file',
            'profile_image.mimes' => 'Only JPG, JPEG and PNG images are allowed',
            'profile_image.max' => 'Image size should be less than 2MB',
        ]);

        // Handle profile image removal
        if ($request->has('remove_profile_image') && $request->remove_profile_image == '1') {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = null;
        }

        // Handle new profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $user->profile_image = $path;
        }

        // Update user details
        $user->name    = $request->name;
        $user->mobile  = $request->mobile;
        $user->address = $request->address ?? '';
        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }
}