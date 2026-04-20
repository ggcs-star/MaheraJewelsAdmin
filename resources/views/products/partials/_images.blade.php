@php
    /** @var \App\Models\Product|null $product */
@endphp

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">
    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800">Product Images</h3>
            <span class="ml-auto text-xs text-gray-400">Upload product photos</span>
        </div>
    </div>

    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Product Images
                </label>
                <div class="relative">
                    <input type="file"
                           name="gallery_images[]"
                           id="imageInput"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#8B2452]/10 file:text-[#8B2452] hover:file:bg-[#8B2452]/20"
                           accept="image/*"
                           multiple>
                </div>
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP • Max 2MB each • Recommended square images</p>
            </div>

            <div class="md:col-span-8">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Preview
                </label>
                <div id="imagePreview"
                     class="border-2 border-dashed border-gray-200 rounded-lg bg-gray-50/30 p-3 flex flex-wrap gap-2 items-start min-h-[200px] transition-all hover:border-[#8B2452]/30"
                     style="cursor:pointer;">

                    @if(!empty($product?->gallery_images) && is_array($product->gallery_images))
                        @foreach($product->gallery_images as $img)
                            <img src="{{ \App\Helpers\S3Helper::url($img) }}"
                                 data-path="{{ $img }}"
                                 class="rounded-lg border-2 border-gray-200 shadow-sm hover:border-[#8B2452] transition-all selectable-gallery-image"
                                 style="width:90px; height:90px; object-fit:cover; cursor:pointer;">
                        @endforeach
                    @elseif(!empty($product?->image_url))
                        <img src="{{ \App\Helpers\S3Helper::url($product->image_url) }}"
                             data-path="{{ $product->image_url }}"
                             class="rounded-lg border-2 border-gray-200 shadow-sm hover:border-[#8B2452] transition-all selectable-gallery-image"
                             style="width:90px; height:90px; object-fit:cover; cursor:pointer;">
                    @else
                        <div class="text-center text-gray-400 w-full py-6" id="imagePlaceholder">
                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm">Click to upload images</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>