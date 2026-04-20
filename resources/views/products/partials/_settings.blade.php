@php
    /** @var \App\Models\Product|null $product */
@endphp

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">
    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800">Product Settings</h3>
            <span class="ml-auto text-xs text-gray-400">Configure product options</span>
        </div>
    </div>

    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Sort Order <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="sort_order"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                       min="0"
                       value="{{ old('sort_order', $product->sort_order ?? 0) }}">
                <p class="text-xs text-gray-400 mt-1">Lower value appears first.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Featured
                </label>
                <select name="is_featured" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                    <option value="0" {{ old('is_featured', $product->is_featured ?? 0) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('is_featured', $product->is_featured ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Show on homepage featured section.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Top Selling
                </label>
                <select name="is_top_selling" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                    <option value="0" {{ old('is_top_selling', $product->is_top_selling ?? 0) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('is_top_selling', $product->is_top_selling ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Highlight as best seller.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white"
                        required>
                    <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Control product availability.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Visibility <span class="text-red-500">*</span>
                </label>
                <select name="visibility"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white"
                        required>
                    <option value="public" {{ old('visibility', $product->visibility ?? 'public') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ old('visibility', $product->visibility ?? 'public') === 'private' ? 'selected' : '' }}>Private</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Public = visible to users.</p>
            </div>
        </div>
    </div>
</div>