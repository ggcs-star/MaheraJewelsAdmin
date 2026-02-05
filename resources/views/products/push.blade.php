@extends('layouts.admin')


@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Push Product to Marketplace</h2>
        <a href="{{ admin_route('products.index') }}" class="btn btn-secondary">
            ← Back to Product
        </a>
    </div>

    <script>
        window.existingVariantPlatformData = @json($existingVariantPlatformData ?? []);
    </script>

    @include('products.partials._errors')

    <!-- ✅ FORM START -->
    <form id="pushProductForm"
          action="{{ admin_route('products.push.store') }}"
          method="POST">
        @csrf

        <input type="hidden" name="product_id" id="selectedProductId">

        @include('products.push._product_selection')
        @include('products.push._product_details')
        @include('products.push._product_variants')

        <!-- ✅ ONLY PREVIEW BUTTON (NO CANCEL) -->
        <div class="text-end mt-4">
            <button type="button"
                    id="previewPushBtn"
                    class="btn btn-primary">
                Push to Selected Platforms
            </button>
        </div>

        <!-- ✅ PREVIEW MODAL INSIDE FORM -->
        <div class="modal fade" id="pushPreviewModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Push Summary</h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Platform</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Final Total</th>
                                </tr>
                            </thead>
                            <tbody id="pushPreviewBody"></tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Edit
                        </button>

                        <!-- ✅ REAL SUBMIT BUTTON -->
                        <button type="submit"
                                class="btn btn-success">
                            Confirm & Push
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- ✅ MODAL END -->

    </form>
    <!-- ✅ FORM END -->

    @include('products.push._variant_platform_modal')

    <script>
        window.platformMap = {
            @foreach($platforms as $platform)
                "{{ $platform->id }}": "{{ ucfirst($platform->name) }}",
            @endforeach
        };
    </script>

</div>
@endsection

@section('scripts')
    @include('products.push.scripts.push-product-js')
@endsection
