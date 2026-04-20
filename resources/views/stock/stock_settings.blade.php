@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Stock Alert Settings</h1>
        <p class="text-sm text-gray-500 mt-0.5">Configure low stock threshold and alert email address</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Alert Configuration</h3>
                </div>
            </div>
            <div class="p-5">
                @if(session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.stock.settings.update') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Low Stock Threshold</label>
                        <div class="flex">
                            <div class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-200 rounded-l-lg flex items-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <input type="number" name="threshold" class="flex-1 px-3 py-2 border border-gray-200 rounded-r-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" value="{{ $setting->threshold }}" required>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">When remaining stock reaches or goes below this number, an email alert will be sent.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Admin Email Address</label>
                        <div class="flex">
                            <div class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-200 rounded-l-lg flex items-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" name="admin_email" class="flex-1 px-3 py-2 border border-gray-200 rounded-r-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" value="{{ $setting->admin_email }}" required>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Low stock alerts will be sent to this email address.</p>
                    </div>

                    <div class="flex gap-3 mt-5">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                        <a href="{{ route('admin.stock.index') }}" class="inline-flex items-center gap-2 px-5 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Stock Management
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">How It Works</h3>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-indigo-700">1</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-0.5">Set Threshold</h4>
                            <p class="text-xs text-gray-500">Set the minimum stock quantity that triggers an alert.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-indigo-700">2</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-0.5">System Checks</h4>
                            <p class="text-xs text-gray-500">System automatically checks all products and variants for low stock.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-indigo-700">3</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-0.5">Email Alert</h4>
                            <p class="text-xs text-gray-500">Email is sent to configured admin email with list of low stock items.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-indigo-700">4</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-0.5">Take Action</h4>
                            <p class="text-xs text-gray-500">Restock the products to maintain inventory levels.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 mb-0.5">Automated Checks</h4>
                        <p class="text-xs text-gray-600">Low stock check runs automatically every 6 hours. You will receive email alerts only when products fall below threshold.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    input:focus {
        outline: none;
    }
</style>
@endsection