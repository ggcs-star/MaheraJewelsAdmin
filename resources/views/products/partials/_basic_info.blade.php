@php
    /** @var \App\Models\Product|null $product */
@endphp

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">
    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800">Basic Information</h3>
            <span class="ml-auto text-xs text-gray-400">Fill in the product details</span>
        </div>
    </div>

    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                               placeholder="Enter product name"
                               value="{{ old('name', $product->name ?? '') }}"
                               required>
                        <p class="text-xs text-gray-400 mt-1">This name will be visible to customers.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            SKU <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="sku"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                               placeholder="Unique product code"
                               value="{{ old('sku', $product->sku ?? '') }}"
                               required>
                        <p class="text-xs text-gray-400 mt-1">Must be unique for inventory tracking.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="slug"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                               placeholder="product-name-slug"
                               value="{{ old('slug', $product->slug ?? '') }}"
                               required>
                        <p class="text-xs text-gray-400 mt-1">Used in product URL.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Product Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                            <input type="number"
                                   name="product_price"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   value="{{ old('product_price', $product->product_price ?? '') }}"
                                   required>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Default price shown before selecting variant.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Short Description
                </label>
                <textarea name="short_description"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                          rows="2"
                          placeholder="One-line summary of the product">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Displayed in product listings.</p>
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Full Description
                </label>
                <textarea name="description"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                          rows="4"
                          placeholder="Detailed product description">{{ old('description', $product->description ?? '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Shown on the product detail page.</p>
            </div>
        </div>
    </div>
</div>