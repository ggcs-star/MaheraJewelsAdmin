<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    /**
     * Display all notification settings.
     */
    public function index()
    {
        $notifications = NotificationSetting::latest()->paginate(10);

        return view('notification-settings.index', compact('notifications'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('notification-settings.create');
    }

    /**
     * Store notification setting.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'nullable|string|max:255',
            'email' => 'required|email|unique:notification_settings,email',
        ]);

        NotificationSetting::create([
            'name'                  => $request->name,
            'email'                 => $request->email,
            'receive_order'         => $request->boolean('receive_order'),
            'receive_cancel'        => $request->boolean('receive_cancel'),
            'receive_registration'  => $request->boolean('receive_registration'),
            'receive_contact'       => $request->boolean('receive_contact'),
            'is_active'             => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.notification-settings.index')
            ->with('success', 'Notification email added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(NotificationSetting $notificationSetting)
    {
        return view(
            'notification-settings.edit',
            compact('notificationSetting')
        );
    }

    /**
     * Update notification setting.
     */
    public function update(Request $request, NotificationSetting $notificationSetting)
    {
        $request->validate([
            'name'  => 'nullable|string|max:255',
            'email' => 'required|email|unique:notification_settings,email,' . $notificationSetting->id,
        ]);

        $notificationSetting->update([
            'name'                  => $request->name,
            'email'                 => $request->email,
            'receive_order'         => $request->boolean('receive_order'),
            'receive_cancel'        => $request->boolean('receive_cancel'),
            'receive_registration'  => $request->boolean('receive_registration'),
            'receive_contact'       => $request->boolean('receive_contact'),
            'is_active'             => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.notification-settings.index')
            ->with('success', 'Notification setting updated successfully.');
    }

    /**
     * Delete notification setting.
     */
    public function destroy(NotificationSetting $notificationSetting)
    {
        $notificationSetting->delete();

        return redirect()
            ->route('admin.notification-settings.index')
            ->with('success', 'Notification setting deleted successfully.');
    }
}