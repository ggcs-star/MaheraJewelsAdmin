<div class="card border-0 shadow-sm rounded-4 mb-4" id="productDetailsCard" style="display:none;">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="fw-bold mb-0">📋 Product Details</h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <img id="productDetailImage" 
                     src="{{ asset('images/no-image.png') }}" 
                     class="rounded-3 border" 
                     style="width:100px; height:100px; object-fit:cover;">
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted d-block">Category</small>
                        <strong id="displayCategory">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Sub Category</small>
                        <strong id="displaySubCategory">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">SKU</small>
                        <strong id="displaySku">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Brand</small>
                        <strong id="displayBrand">-</strong>
                    </div>
                    <div class="col-md-3 mt-2">
                        <small class="text-muted d-block">Warehouse</small>
                        <strong id="displayWarehouse">-</strong>
                    </div>
                    <div class="col-md-3 mt-2">
                        <small class="text-muted d-block">Slug</small>
                        <strong id="displaySlug">-</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>