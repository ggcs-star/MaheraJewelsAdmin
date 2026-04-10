<?php

namespace App\Http\Controllers;

use App\Models\StockSetting;
use Illuminate\Http\Request;

class StockSettingController extends Controller
{
    public function index()
    {
        $setting = StockSetting::first();
        if(!$setting) {
            $setting = StockSetting::create([
                'threshold' => 5,
                'admin_email' => 'admin@gmail.com'
            ]);
        }
        return view('stock.stock_settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = StockSetting::first();
        $setting->update([
            'threshold' => $request->threshold,
            'admin_email' => $request->admin_email
        ]);
        return back()->with('success', 'Settings updated successfully');
    }
}