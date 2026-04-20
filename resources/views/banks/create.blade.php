@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Add Bank</h1>
            <p class="text-gray-500 text-xs mt-0.5">Create a new bank account</p>
        </div>
        <a href="{{ admin_route('banks.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5">
            <form method="POST" action="{{ admin_route('banks.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Bank Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm @error('name') border-red-500 @enderror"
                        placeholder="e.g. State Bank of India">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Bank Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="code"
                        value="{{ old('code') }}"
                        class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm @error('code') border-red-500 @enderror"
                        placeholder="e.g. SBI">
                    @error('code')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white @error('status') border-red-500 @enderror">
                        <option value="">Select status</option>
                        <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm" style="background: #8B2452; color: white;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Bank
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection