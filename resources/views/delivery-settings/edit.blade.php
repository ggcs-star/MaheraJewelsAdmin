@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ admin_route('delivery-settings.index') }}" class="hover:text-[#8B2452] transition-colors">Delivery Settings</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600">Edit Delivery Setting</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Delivery Setting</h1>
            <p class="text-sm text-gray-500 mt-0.5">Update delivery and platform fees</p>
        </div>
        <a href="{{ admin_route('delivery-settings.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all border border-gray-200 text-gray-700 hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-700 mb-1">Please fix the following errors:</p>
                    <ul class="text-xs text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ admin_route('delivery-settings.update', $deliverySetting->id) }}">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Delivery Fee
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                            <input type="number" step="0.01" name="delivery_fee" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" value="{{ old('delivery_fee', $deliverySetting->delivery_fee) }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Platform Fee
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                            <input type="number" step="0.01" name="platform_fee" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" value="{{ old('platform_fee', $deliverySetting->platform_fee) }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Tax (%)
                        </label>
                        <input type="number" step="0.01" name="tax_percent" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" value="{{ old('tax_percent', $deliverySetting->tax_percent) }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Free Delivery Above</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                            <input type="number" step="0.01" name="free_delivery_above" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" value="{{ old('free_delivery_above', $deliverySetting->free_delivery_above) }}">
                        </div>
                    </div>
                </div>

                <!-- ✅ Order Automation Section -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Order Automation
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Auto Confirm After (Minutes)
                            </label>
                            <input
                                type="number"
                                name="auto_confirm_minutes"
                                min="1"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                                value="{{ old('auto_confirm_minutes', $deliverySetting->auto_confirm_minutes ?? 60) }}">
                            <p class="text-xs text-gray-500 mt-2">
                                Pending orders will automatically become Confirmed after this time.
                            </p>
                        </div>

                        <div class="flex items-center pt-1">
                            <input
                                type="checkbox"
                                id="auto_confirm_enabled"
                                name="auto_confirm_enabled"
                                value="1"
                                {{ old('auto_confirm_enabled', $deliverySetting->auto_confirm_enabled ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 focus:ring-[#8B2452] h-4 w-4">
                            <label
                                for="auto_confirm_enabled"
                                class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                                Enable Auto Confirm Orders
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ Footer Buttons -->
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: #8B2452; color: white;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Update
                </button>
            </div>
        </div>
    </form>
</div>
@endsection