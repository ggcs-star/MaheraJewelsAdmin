<div class="card mb-4 shadow-sm" id="platformSelectionCard" style="display: none;">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-warning fs-5">🌐</span>
        <span>Select Platforms</span>
    </div>

    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold mb-3">
                    Choose platforms where you want to push this product <span class="text-danger">*</span>
                </label>
                
                <div class="d-flex flex-wrap gap-4">
                    <!-- Website Checkbox -->
                    <div class="form-check form-check-lg">
                        <input class="form-check-input platform-checkbox" 
                               type="checkbox" 
                               name="platforms[]" 
                               value="website" 
                               id="platformWebsite"
                               data-platform="website">
                        <label class="form-check-label fw-semibold" for="platformWebsite">
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                🌐 Website
                            </span>
                        </label>
                    </div>

                    <!-- Flipkart Checkbox -->
                    <div class="form-check form-check-lg">
                        <input class="form-check-input platform-checkbox" 
                               type="checkbox" 
                               name="platforms[]" 
                               value="flipkart" 
                               id="platformFlipkart"
                               data-platform="flipkart">
                        <label class="form-check-label fw-semibold" for="platformFlipkart">
                            <span class="badge bg-yellow text-dark fs-6 px-3 py-2" style="background-color: #ff9f00;">
                                🛒 Flipkart
                            </span>
                        </label>
                    </div>

                    <!-- Amazon Checkbox -->
                    <div class="form-check form-check-lg">
                        <input class="form-check-input platform-checkbox" 
                               type="checkbox" 
                               name="platforms[]" 
                               value="amazon" 
                               id="platformAmazon"
                               data-platform="amazon">
                        <label class="form-check-label fw-semibold" for="platformAmazon">
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                📦 Amazon
                            </span>
                        </label>
                    </div>
                </div>

                <div class="form-text mt-2">
                    Select one or more platforms. Each platform will have separate pricing and inventory settings.
                </div>
            </div>
        </div>
    </div>
</div>
