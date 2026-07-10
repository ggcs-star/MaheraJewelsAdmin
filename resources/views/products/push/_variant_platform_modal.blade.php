<div class="modal fade" id="variantPlatformModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">⚙️ Configure Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- ✅ Hidden input for variant ID --}}
                <input type="hidden" id="modalVariantId" value="">

                @include('products.push._platform_selection')

                <div id="platformPricingSection" style="display: none;">
                    @if(isset($platforms) && count($platforms) > 0)
                        @foreach($platforms as $platform)
                            @php
                                // ✅ Get variant ID from hidden input
                                $currentVariantId = request()->input('variant_id', null);
                            @endphp
                            @include('products.push._platform_pricing', [
                                'platform' => $platform,
                                'variantId' => $currentVariantId
                            ])
                        @endforeach
                    @else
                        <div class="alert alert-warning">No platforms configured.</div>
                    @endif
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" id="saveVariantData">Save Variant</button>
            </div>

        </div>
    </div>
</div>