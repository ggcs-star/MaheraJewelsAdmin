<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Services\InstagramGraphService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstagramController extends Controller
{
    protected $instagram;

    public function __construct(InstagramGraphService $instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * Facebook Login Redirect
     */
    public function redirect()
    {
        return redirect()->away(
            $this->instagram->getLoginUrl()
        );
    }

    /**
     * Facebook Callback
     */
    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Authorization failed.');
        }

        // STEP 1 - Short Lived Token
        $short = $this->instagram->getShortLivedToken($request->code);

        if (
            !isset($short['body']['access_token'])
        ) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Unable to get access token.');
        }

        // STEP 2 - Long Lived Token
        $long = $this->instagram->getLongLivedToken(
            $short['body']['access_token']
        );

        if (
            $long['status'] != 200 ||
            !isset($long['body']['access_token'])
        ) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Unable to generate long lived token.');
        }

        $accessToken = $long['body']['access_token'];

        // STEP 3 - Facebook Pages
        $pages = $this->instagram->getPages($accessToken);

        if (
            !isset($pages['data']) ||
            empty($pages['data'])
        ) {
            return back()->with(
                'error',
                'No Facebook Page found.'
            );
        }

        $page = $pages['data'][0];

        // STEP 4 - Instagram Business Account
        $business = $this->instagram->getInstagramBusiness(
            $page['id'],
            $accessToken
        );

        if (
            !isset($business['instagram_business_account']['id'])
        ) {
            return back()->with(
                'error',
                'Instagram Business Account not connected.'
            );
        }

        $instagramId = $business['instagram_business_account']['id'];

        // STEP 5 - Instagram Profile
        $profile = $this->instagram->getProfile(
            $instagramId,
            $accessToken
        );

        // STEP 6 - Save / Update Single Instagram Account
        SocialAccount::updateOrCreate(

            [
                'platform' => 'instagram',
            ],

            [
                'user_id' => Auth::id(),

                'facebook_user_id' => $page['id'],

                'page_id' => $page['id'],

                'instagram_business_id' => $instagramId,

                'instagram_username' => $profile['username'] ?? null,

                'access_token' => $accessToken,

                'expires_at' => now()->addSeconds(
                    $long['body']['expires_in'] ?? 5183944
                ),

                'is_active' => true,
            ]
        );

        return redirect()
            ->route('instagram.reels')
            ->with(
                'success',
                'Instagram connected successfully.'
            );
    }

    /**
     * Show Reels
     */
    public function reels()
    {
        $account = SocialAccount::where('platform', 'instagram')
            ->where('is_active', true)
            ->first();

        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Instagram account not connected.'
            ], 404);
        }

     $media = $this->instagram->getMedia(
    $account->instagram_business_id,
    $account->access_token
);

return response()->json([
    'success' => true,
    'username' => $account->instagram_username,
    'total' => count($media['data'] ?? []),
    'posts' => $media['data'] ?? [],
]);
    }

public function disconnect()
{
    SocialAccount::where('platform', 'instagram')->delete();

    return redirect()
        ->route('admin.instagram')
        ->with('success', 'Instagram disconnected successfully.');
}
}