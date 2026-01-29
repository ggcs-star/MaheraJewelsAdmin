<div class="modal fade" id="variantPlatformModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">⚙️ Configure Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- ✅ ONLY HERE --}}
                @include('products.push._platform_selection')
                @include('products.push._platform_pricing')
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" id="saveVariantData">Save Variant</button>
            </div>

        </div>
    </div>
</div>
