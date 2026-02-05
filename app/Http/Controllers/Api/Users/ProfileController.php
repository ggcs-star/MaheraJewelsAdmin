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
                    'profile_image' => $user->profile_image
                        ? url($user->profile_image)
                        : null,
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
        // dd($request->all());
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|string', 
        ]);

        try {
            $user = $request->user();

            $updateData = [];

            if (array_key_exists('name', $data)) {
                $updateData['name'] = $data['name'];
            }

            if (array_key_exists('mobile', $data)) {
                $updateData['mobile'] = $data['mobile'];
            }

            if (array_key_exists('address', $data)) {
                $updateData['address'] = $data['address'];
            }

            if (!empty($data['profile_image'])) {
                $updateData['profile_image'] = $this->handleAvatar($data['profile_image'], $user);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'address' => $user->address,
                    'profile_image' => $user->profile_image
                        ? url($user->profile_image)
                        : null,
                    'created_at' => $user->created_at?->toISOString(),
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

    private function handleAvatar(string $avatar, $user): string
    {
        if (filter_var($avatar, FILTER_VALIDATE_URL)) {
            return $avatar;
        }

        if (!str_contains($avatar, 'base64')) {
            throw new \Exception('Invalid image data');
        }

        [$meta, $content] = explode(',', $avatar);

        if (!preg_match('/data:image\/(png|jpg|jpeg|webp)/', $meta, $matches)) {
            throw new \Exception('Unsupported image type');
        }

        $extension = $matches[1];
        $image = base64_decode($content);

        if ($image === false) {
            throw new \Exception('Base64 decode failed');
        }

        if ($user->profile_image && str_starts_with($user->profile_image, 'storage/')) {
            $oldPath = str_replace('storage/', '', $user->profile_image);
            Storage::disk('public')->delete($oldPath);
        }

        $fileName = 'profile_' . $user->id . '_' . time() . '.' . $extension;
        $path = 'profiles/' . $fileName;

        Storage::disk('public')->put($path, $image);

        return 'storage/' . $path;
    }


}
