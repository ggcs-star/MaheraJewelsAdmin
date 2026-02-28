<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        try {
            $user = auth()->user();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->mobile,
                    'profile_image' => $user->profile_image,
                    'created_at' => $user->created_at?->toISOString(),
                ]
            ]);

        } catch (Throwable $e) {

            Log::error('Get User Profile API Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch profile details'
            ], 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'          => 'nullable|string|max:255',
            'mobile'        => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $user = $request->user();
            $updateData = [];

            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }

            if (isset($data['mobile'])) {
                $updateData['mobile'] = $data['mobile'];
            }

            if (isset($data['address'])) {
                $updateData['address'] = $data['address'];
            }

            if ($request->hasFile('profile_image')) {
                $updateData['profile_image'] = $this->handleAvatar(
                    $request->file('profile_image'),
                    $user
                );
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'id'            => $user->id,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'mobile'        => $user->mobile,
                    'address'       => $user->address,
                    'profile_image' => $user->profile_image
                        ? Storage::disk('s3')->url($user->profile_image)
                        : null,
                    'created_at'    => $user->created_at?->toISOString(),
                ]
            ]);

        } catch (Throwable $e) {

            Log::error('Update User Profile API Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update profile'
            ], 500);
        }
    }
    private function handleAvatar($file, $user): string
    {
        if ($user->profile_image) {
            Storage::disk('s3')->delete($user->profile_image);
        }

        $userNameSlug = Str::slug($user->name, '_');

        $path = 'admin/profile/' . $userNameSlug;

        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        return Storage::disk('s3')->putFileAs(
            $path,
            $file,
            $fileName,
            'public'
        );
    }


}
