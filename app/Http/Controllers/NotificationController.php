<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationToken;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected FirebaseService $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * Save Browser FCM Token
     */
    public function saveToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'browser'   => 'nullable|string',
            'platform'  => 'nullable|string',
        ]);

        NotificationToken::updateOrCreate(
            [
                'fcm_token' => $request->fcm_token,
            ],
            [
                'user_id'    => Auth::id(),
                'browser'    => $request->browser,
                'platform'   => $request->platform,
                'ip_address' => $request->ip(),
                'last_seen'  => now(),
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Token saved successfully.'
        ]);
    }

    /**
     * Send Notification To All Users
     */
    public function sendToAll(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => 'broadcast',
            'is_read' => false,
        ]);

        $this->firebase->sendToAll(
            $request->title,
            $request->message
        );

        return response()->json([
            'status' => true,
            'message' => 'Notification sent successfully.'
        ]);
    }

    /**
     * Send Notification To Single User
     */
    public function sendToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => 'personal',
            'is_read' => false,
        ]);

        $this->firebase->sendToUser(
            $request->user_id,
            $request->title,
            $request->message
        );

        return response()->json([
            'status' => true,
            'message' => 'Notification sent.'
        ]);
    }

    
public function index()
{
    Notification::where(function ($query) {
        $query->where('user_id', Auth::id())
              ->orWhereNull('user_id');
    })
    ->where('is_read', false)
    ->update([
        'is_read' => true
    ]);

    $notifications = Notification::where(function ($query) {
        $query->where('user_id', Auth::id())
              ->orWhereNull('user_id');
    })
    ->latest()
    ->paginate(20);

    return view(
        'admin.notifications.index',
        compact('notifications')
    );
}
   
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        $notification->update([
            'is_read' => true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Marked as read.'
        ]);
    }

    /**
     * Delete Notification
     */
    public function destroy($id)
    {
        Notification::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Deleted successfully.'
        ]);
    }
    
public function testNotification()
{
    $token = NotificationToken::value('fcm_token');

    if (!$token) {
        return response()->json([
            'success' => false,
            'message' => 'No FCM Token Found'
        ]);
    }

    $response = $this->firebase->sendToToken(
        $token,
        'Test Notification',
        'Firebase Push Notification Working!',
        [
            'url' => url('/admin/dashboard')
        ]
    );

    dd($response);
}
}