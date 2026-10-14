<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
        <h3 class="text-base font-bold text-gray-800">Categories & Subcategories</h3>
        <p class="text-xs text-gray-500 mt-1">
            Select categories first, then choose their subcategories.
        </p>
    </div>

    @php
        $selectedCategoryIds = collect(old(
            'category_ids',
            isset($product) ? $product->categories->pluck('id')->all() : []
        ))->map(fn ($id) => (int) $id)->all();

        if (empty($selectedCategoryIds) && isset($product) && $product->category_id) {
            $selectedCategoryIds = [(int) $product->category_id];
        }

        $selectedSubcategoryIds = collect(old(
            'subcategory_ids',
            isset($product) && isset($product->subcategories)
                ? $product->subcategories->pluck('id')->all()
                : []
        ))->map(fn ($id) => (int) $id)->all();
    @endphp

    <div class="p-5 space-y-4">
        @foreach ($categories->whereNull('parent_id') as $category)
            @php
                $children = $categories->where('parent_id', $category->id)->values();
                $categorySelected = in_array((int) $category->id, $selectedCategoryIds, true);
            @endphp

            <div class="category-group border border-gray-200 rounded-xl overflow-hidden"
                 data-category-id="{{ $category->id }}">

                <label class="flex items-center gap-3 p-4 cursor-pointer hover:bg-gray-50">
                    <input
                        type="checkbox"
                        name="category_ids[]"
                        value="{{ $category->id }}"
                        class="parent-category-checkbox rounded text-[#8B2452] focus:ring-[#8B2452]"
                        {{ $categorySelected ? 'checked' : '' }}
                    >

                    <span class="font-semibold text-sm text-gray-800">
                        {{ $category->name }}
                    </span>

                    <span class="ml-auto text-xs text-gray-400">
                        {{ $children->count() }} subcategories
                    </span>
                </label>

                <div class="subcategory-panel {{ $categorySelected ? '' : 'hidden' }}
                            border-t border-gray-100 bg-gray-50 p-4">

                    @if ($children->isNotEmpty())
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-3">
                            Select subcategories
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach ($children as $subcategory)
                                <label class="flex items-center gap-2 p-2 rounded-lg bg-white border border-gray-100 cursor-pointer hover:border-[#8B2452]/40">
                                    <input
                                        type="checkbox"
                                        name="subcategory_ids[]"
                                        value="{{ $subcategory->id }}"
                                        class="subcategory-checkbox rounded text-[#8B2452] focus:ring-[#8B2452]"
                                        data-parent-id="{{ $category->id }}"
                                        {{ in_array((int) $subcategory->id, $selectedSubcategoryIds, true) ? 'checked' : '' }}
                                    >

                                    <span class="text-sm text-gray-700">
                                        {{ $subcategory->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">
                            No subcategories available for this category.
                        </p>
                    @endif
                </div>
            </div>
        @endforeach

        @error('category_ids')
            <p class="text-xs text-red-500">{{ $message }}</p>
        @enderror

        @error('category_ids.*')
            <p class="text-xs text-red-500">{{ $message }}</p>
        @enderror

        @error('subcategory_ids')
            <p class="text-xs text-red-500">{{ $message }}</p>
        @enderror

        @error('subcategory_ids.*')
            <p class="text-xs text-red-500">{{ $message }}</p>
        @enderror

        <div class="rounded-lg bg-[#8B2452]/5 border border-[#8B2452]/10 p-3">
            <p class="text-sm text-gray-700">
                Selected categories:
                <strong id="selectedCategoryCount" class="text-[#8B2452]">0</strong>
            </p>
            <p class="text-sm text-gray-700 mt-1">
                Selected subcategories:
                <strong id="selectedSubcategoryCount" class="text-[#8B2452]">0</strong>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const groups = document.querySelectorAll('.category-group');

    function updateSelection() {
        let categoryCount = 0;
        let subcategoryCount = 0;

        groups.forEach(function (group) {
            const parent = group.querySelector('.parent-category-checkbox');
            const panel = group.querySelector('.subcategory-panel');
            const children = group.querySelectorAll('.subcategory-checkbox');

            if (parent.checked) {
                categoryCount++;
                panel?.classList.remove('hidden');
            } else {
                panel?.classList.add('hidden');

                // Unselect child categories when the parent is unchecked.
                children.forEach(function (child) {
                    child.checked = false;
                });
            }

            children.forEach(function (child) {
                if (child.checked) subcategoryCount++;
            });
        });

        document.getElementById('selectedCategoryCount').textContent = categoryCount;
        document.getElementById('selectedSubcategoryCount').textContent = subcategoryCount;
    }

    groups.forEach(function (group) {
        const parent = group.querySelector('.parent-category-checkbox');

        parent.addEventListener('change', updateSelection);
        group.querySelectorAll('.subcategory-checkbox').forEach(function (child) {
            child.addEventListener('change', function () {
                if (child.checked && !parent.checked) {
                    parent.checked = true;
                }
                updateSelection();
            });
        });
    });

    updateSelection();
});
</script>