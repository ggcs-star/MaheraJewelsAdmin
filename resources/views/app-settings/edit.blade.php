@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ admin_route('app-settings.index') }}" class="hover:text-[#8B2452] transition-colors">App Settings</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600">Edit Application Settings</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Application Settings</h1>
            <p class="text-sm text-gray-500 mt-0.5">Update application configuration</p>
        </div>
        <a href="{{ admin_route('app-settings.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all border border-gray-200 text-gray-700 hover:bg-gray-50">
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

    <form method="POST" action="{{ admin_route('app-settings.update', $appSetting) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Application Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="app_name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm @error('app_name') border-red-500 @enderror" value="{{ old('app_name', $appSetting->app_name) }}" required>
                        @error('app_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="is_active" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white @error('is_active') border-red-500 @enderror" required>
                            <option value="1" {{ $appSetting->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$appSetting->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Application Logo</label>
                        <input type="file" name="app_logo" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#8B2452]/10 file:text-[#8B2452] hover:file:bg-[#8B2452]/20">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to keep current logo</p>
                        @if($appSetting->app_logo_url)
                            <div class="mt-2">
                                <img src="{{ $appSetting->app_logo_url }}" class="h-16 w-auto rounded-lg border border-gray-200 object-contain p-1" alt="app logo">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Splash Screen Logo</label>
                        <input type="file" name="splash_logo" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#8B2452]/10 file:text-[#8B2452] hover:file:bg-[#8B2452]/20">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to keep current logo</p>
                        @if($appSetting->splash_logo_url)
                            <div class="mt-2">
                                <img src="{{ $appSetting->splash_logo_url }}" class="h-16 w-auto rounded-lg border border-gray-200 object-contain p-1" alt="splash logo">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Header Logo</label>
                        <input type="file" name="header_logo" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#8B2452]/10 file:text-[#8B2452] hover:file:bg-[#8B2452]/20">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to keep current logo</p>
                        @if($appSetting->header_logo_url)
                            <div class="mt-2">
                                <img src="{{ $appSetting->header_logo_url }}" class="h-16 w-auto rounded-lg border border-gray-200 object-contain p-1" alt="header logo">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection