<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Helpers\S3Helper;
class AppSettingController extends Controller
{
    public function index()
    {
        $settings = AppSetting::latest()->get();

        return view('app-settings.index', compact('settings'));
    }

    public function create()
    {
        return view('app-settings.create');
    }

    public function store(Request $request)
    {
        if (AppSetting::exists()) {
            return redirect()
                ->to(admin_route('app-settings.index'))
                ->with(
                    'error',
                    'Settings already exist. Please edit the existing record.'
                );
        }

        $data = $request->validate([
            'app_name' => 'required|string|max:255',

            'app_logo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'splash_logo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'header_logo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_active' =>
                'required|boolean',
        ]);

        if ($request->hasFile('app_logo')) {

            $data['app_logo'] =
                S3Helper::store(
                    $request->file('app_logo'),
                    'admin/app-settings/applogo'
                );
        }

        if ($request->hasFile('splash_logo')) {

            $data['splash_logo'] =
                S3Helper::store(
                    $request->file('splash_logo'),
                    'admin/app-settings/splash'
                );
        }

        if ($request->hasFile('header_logo')) {

           $data['header_logo'] =
                S3Helper::store(
                    $request->file('header_logo'),
                    'admin/app-settings/header'
                );
        }

        AppSetting::create($data);
        Cache::forget('app_settings');

        return redirect()
            ->to(admin_route('app-settings.index'))
            ->with(
                'success',
                'Application settings created successfully.'
            );
    }

    public function show(AppSetting $appSetting)
    {
        return view(
            'app-settings.show',
            compact('appSetting')
        );
    }

    public function edit(AppSetting $appSetting)
    {
        return view(
            'app-settings.edit',
            compact('appSetting')
        );
    }

    public function update(Request $request, AppSetting $appSetting)
    {
        $data = $request->validate([
            'app_name' => 'required|string|max:255',

            'app_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'splash_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'header_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('app_logo')) {

            if ($appSetting->app_logo) {
                S3Helper::delete($appSetting->app_logo);
            }

            $data['app_logo'] =
                S3Helper::store(
                    $request->file('app_logo'),
                    'admin/app-settings/applogo'
                );
        }

        if ($request->hasFile('splash_logo')) {

            if ($appSetting->splash_logo) {
                S3Helper::delete($appSetting->splash_logo);
            }

            $data['splash_logo'] =
                S3Helper::store(
                    $request->file('splash_logo'),
                    'admin/app-settings/splash'
                );
        }

        if ($request->hasFile('header_logo')) {

            if ($appSetting->header_logo) {
                S3Helper::delete($appSetting->header_logo);
            }

            $data['header_logo'] =
                S3Helper::store(
                    $request->file('header_logo'),
                    'admin/app-settings/header'
                );
        }

        $appSetting->update($data);
        Cache::forget('app_settings');

        return redirect()
            ->to(admin_route('app-settings.index'))
            ->with('success', 'Application settings updated successfully.');
    }

    public function destroy(AppSetting $appSetting)
    {
        if ($appSetting->app_logo) {
            S3Helper::delete($appSetting->app_logo);
        }

        if ($appSetting->splash_logo) {
            S3Helper::delete($appSetting->splash_logo);
        }

        if ($appSetting->header_logo) {
            S3Helper::delete($appSetting->header_logo);
        }

        $appSetting->delete();
        Cache::forget('app_settings');

        return back()->with(
            'success',
            'Application settings deleted.'
        );
    }
}