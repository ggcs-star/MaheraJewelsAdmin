@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="space-y-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Variants Master</h1>
        <p class="text-sm text-gray-500 mt-0.5">Create generic variant types (Color, Size, Weight, Dimension) and manage their values</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <h3 class="text-base font-bold text-gray-800">Add Variant Type</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="POST" action="{{ route('admin.variants.store') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                @csrf
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Variant Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Color / Size / Weight" required>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Input Type</label>
                    <select name="input_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white" required>
                        <option value="color">Color Picker</option>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Has Dimensions?</label>
                    <select name="has_dimensions" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white" required>
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    @forelse($variants as $variant)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="text-base font-bold text-gray-800">{{ $variant->name }}</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600">{{ strtoupper($variant->input_type) }}</span>
                </div>
                <form method="POST" action="{{ route('admin.variants.destroy', $variant) }}" onsubmit="return confirm('Delete this variant and all its values?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all" style="background: #dc2626; color: white; border: none;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('admin.variants.values.store', $variant) }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-5">
                    @csrf
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Value</label>
                        <input type="text" name="value" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Red / XL / 1kg" required>
                    </div>
                    @if($variant->input_type === 'color')
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Color</label>
                            <input type="color" name="color" class="w-full h-10 border border-gray-200 rounded-lg cursor-pointer">
                        </div>
                    @endif
                    @if($variant->has_dimensions)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Height</label>
                            <input type="number" name="height" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Height">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Width</label>
                            <input type="number" name="width" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Width">
                        </div>
                    @endif
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Value
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[500px]">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Value</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Color</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Height</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Width</th>
                                <th class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($variant->values as $value)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $value->value }}</td>
                                <td class="px-4 py-2">
                                    @if($variant->input_type === 'color' && $value->color)
                                        <span class="inline-block w-6 h-6 rounded border border-gray-300" style="background: {{ $value->color }};"></span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $value->height ?? '—' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $value->width ?? '—' }}</td>
                                <td class="px-4 py-2 text-center">
                                    <form method="POST" action="{{ route('admin.variants.values.destroy', $value) }}" onsubmit="return confirm('Delete this value?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-400">No values added yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <p class="text-gray-500 text-sm">No variants created yet</p>
        </div>
    @endforelse
</div>
@endsection