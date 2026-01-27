@verbatim
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('productSelect');
    const productDetailsCard = document.getElementById('productDetailsCard');
    const platformSelectionCard = document.getElementById('platformSelectionCard');
    const platformPricingSection = document.getElementById('platformPricingSection');
    const variantImagesCard = document.getElementById('variantImagesCard');
    const variantsContainer = document.getElementById('variantsContainer');
    
    // Debug: Check if all elements exist
    if (!productSelect) {
        console.error('Product select element not found!');
        return;
    }
    
    if (!productDetailsCard) console.warn('Product details card not found');
    if (!platformSelectionCard) console.warn('Platform selection card not found');
    if (!platformPricingSection) console.warn('Platform pricing section not found');
    if (!variantImagesCard) console.warn('Variant images card not found');
    if (!variantsContainer) console.warn('Variants container not found');
    
    let selectedProductData = null;

    // Product Selection Handler
    productSelect.addEventListener('change', function() {
        console.log('Product selection changed:', this.value);
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            try {
                console.log('Processing product selection...');
                
                // Get product data from data attributes
                let variants = [];
                try {
                    // Try dataset first
                    if (selectedOption.dataset.variants) {
                        variants = JSON.parse(selectedOption.dataset.variants);
                        console.log('Parsed variants from dataset:', variants.length);
                    } else {
                        // Fallback to getAttribute
                        const variantsData = selectedOption.getAttribute('data-variants');
                        console.log('Variants data from attribute:', variantsData ? 'Found' : 'Not found');
                        if (variantsData) {
                            variants = JSON.parse(variantsData);
                            console.log('Parsed variants from attribute:', variants.length);
                        }
                    }
                    
                    if (variants && variants.length > 0) {
                        console.log('Variants loaded:', variants);
                    } else {
                        console.warn('No variants found for this product');
                    }
                } catch (e) {
                    console.error('Error parsing variants:', e, selectedOption);
                    variants = [];
                }

                selectedProductData = {
                    category: selectedOption.dataset.category || 'N/A',
                    subcategory: selectedOption.dataset.subcategory || 'N/A',
                    sku: selectedOption.dataset.sku || 'N/A',
                    slug: selectedOption.dataset.slug || 'N/A',
                    brand: selectedOption.dataset.brand || 'N/A',
                    variants: variants
                };

                console.log('Product data:', selectedProductData);

                // Show product details
                displayProductDetails(selectedProductData);
                
                // Show platform selection
                if (platformSelectionCard) {
                    console.log('Showing platform selection card');
                    platformSelectionCard.style.display = 'block';
                    console.log('Platform selection card style set to block');
                } else {
                    console.error('Platform selection card not found!');
                }
                
                // Show variant images section
                console.log('Loading variants...');
                loadVariants(selectedProductData.variants);
                
                // Scroll to platform selection for better UX
                setTimeout(() => {
                    if (platformSelectionCard) {
                        platformSelectionCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }, 300);
            } catch (error) {
                console.error('Error processing product selection:', error);
                alert('Error loading product details. Please try again.');
            }
        } else {
            console.log('No product selected, hiding all sections');
            // Hide all sections
            if (productDetailsCard) productDetailsCard.style.display = 'none';
            if (platformSelectionCard) platformSelectionCard.style.display = 'none';
            if (platformPricingSection) platformPricingSection.style.display = 'none';
            if (variantImagesCard) variantImagesCard.style.display = 'none';
        }
    });

    // Display Product Details
    function displayProductDetails(data) {
        console.log('displayProductDetails called with:', data);
        
        if (!productDetailsCard) {
            console.error('Product details card not found!');
            return;
        }
        
        const categoryEl = document.getElementById('displayCategory');
        const subCategoryEl = document.getElementById('displaySubCategory');
        const skuEl = document.getElementById('displaySku');
        const slugEl = document.getElementById('displaySlug');
        const brandEl = document.getElementById('displayBrand');
        
        if (categoryEl) categoryEl.textContent = data.category || 'N/A';
        if (subCategoryEl) subCategoryEl.textContent = data.subcategory || 'N/A';
        if (skuEl) skuEl.textContent = data.sku || 'N/A';
        if (slugEl) slugEl.textContent = data.slug || 'N/A';
        if (brandEl) brandEl.textContent = data.brand || 'N/A';
        
        productDetailsCard.style.display = 'block';
        console.log('Product details card displayed');
    }

    // Load Variants
    function loadVariants(variants) {
        console.log('loadVariants called with:', variants);
        console.log('Variants type:', typeof variants, 'Is array:', Array.isArray(variants));
        
        if (!variantsContainer) {
            console.error('Variants container not found!');
            return;
        }
        
        // Ensure variants is an array
        if (!variants) {
            variants = [];
        }
        
        if (!Array.isArray(variants)) {
            console.warn('Variants is not an array, converting...', variants);
            variants = Object.values(variants);
        }
        
        if (variants.length === 0) {
            console.log('No variants found, showing warning');
            variantsContainer.innerHTML = `
                <div class="alert alert-warning">
                    <strong>No variants found!</strong> This product doesn't have any variants in inventory.
                </div>
            `;
            if (variantImagesCard) {
                variantImagesCard.style.display = 'block';
                console.log('Variant images card displayed (no variants)');
            } else {
                console.error('Variant images card not found!');
            }
            return;
        }
        
        console.log('Loading', variants.length, 'variants');

        let html = '<div class="row g-3">';
        
        variants.forEach((variant, index) => {
            console.log('Processing variant', index, ':', variant);
            const variantType = variant.variant_type || variant.variantType || 'Default';
            const variantValue = variant.variant_value || variant.variantValue || 'N/A';
            const variantSku = variant.sku_suffix || variant.skuSuffix || '';
            const variantId = variant.id || index;
            
            console.log('Variant details:', { variantType, variantValue, variantSku, variantId });
            
            html += `
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                Variant: ${variantType} - ${variantValue}
                                ${variantSku ? `<small class="text-muted">(${variantSku})</small>` : ''}
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Upload Image for ${variantType} - ${variantValue}
                                </label>
                                <input type="file" 
                                       name="variant_images[${index}][image]" 
                                       class="form-control" 
                                       accept="image/*">
                                <input type="hidden" 
                                       name="variant_images[${index}][variant_id]" 
                                       value="${variantId}">
                                <input type="hidden" 
                                       name="variant_images[${index}][variant_type]" 
                                       value="${variantType}">
                                <input type="hidden" 
                                       name="variant_images[${index}][variant_value]" 
                                       value="${variantValue}">
                                <div class="form-text">
                                    Upload variant-specific image
                                </div>
                            </div>
                            
                            <div class="preview-container" id="preview-${index}">
                                <small class="text-muted">Preview will appear after selection</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        variantsContainer.innerHTML = html;
        
        // Force show variant images card
        if (variantImagesCard) {
            variantImagesCard.style.display = 'block';
            variantImagesCard.style.visibility = 'visible';
            console.log('Variant images card displayed with', variants.length, 'variants');
            
            // Force a reflow to ensure display
            variantImagesCard.offsetHeight;
        } else {
            console.error('Variant images card not found!');
        }

        // Add image preview handlers
        attachImagePreviewHandlers();
        
        // Scroll to variants section
        setTimeout(() => {
            if (variantImagesCard) {
                variantImagesCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 200);
    }

    // Attach Image Preview Handlers
    function attachImagePreviewHandlers() {
        const fileInputs = document.querySelectorAll('input[type="file"][name^="variant_images"]');
        
        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    const index = this.name.match(/\[(\d+)\]/)[1];
                    const previewContainer = document.getElementById(`preview-${index}`);
                    
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `
                            <img src="${e.target.result}" 
                                 class="img-thumbnail mt-2" 
                                 style="max-width: 200px; max-height: 200px;">
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // Price Calculation Function
    function calculatePlatformPrice(platform) {
        const priceInput = document.getElementById(`${platform}_price`);
        const discountInput = document.getElementById(`${platform}_discount`);
        const quantityInput = document.getElementById(`${platform}_quantity`);
        
        const price = parseFloat(priceInput?.value || 0);
        const discount = parseFloat(discountInput?.value || 0);
        const quantity = parseFloat(quantityInput?.value || 0);
        
        // Calculate price after discount per product
        const discountAmount = (price * discount) / 100;
        const priceAfterDiscount = price - discountAmount;
        
        // Calculate total
        const total = priceAfterDiscount * quantity;
        
        // Update display
        const priceAfterDiscountEl = document.getElementById(`${platform}_price_after_discount`);
        const totalEl = document.getElementById(`${platform}_total`);
        const formulaEl = document.getElementById(`${platform}_formula`);
        
        if (priceAfterDiscountEl) {
            priceAfterDiscountEl.textContent = `₹${priceAfterDiscount.toFixed(2)}`;
        }
        
        if (totalEl) {
            totalEl.textContent = `₹${total.toFixed(2)}`;
        }
        
        // Update formula display
        if (formulaEl && price > 0 && quantity > 0) {
            if (discount > 0) {
                formulaEl.innerHTML = `
                    <div class="small">
                        <div>₹${price.toFixed(2)} - ${discount}% = ₹${priceAfterDiscount.toFixed(2)}</div>
                        <div>₹${priceAfterDiscount.toFixed(2)} × ${quantity} = ₹${total.toFixed(2)}</div>
                    </div>
                `;
            } else {
                formulaEl.innerHTML = `
                    <div class="small">
                        ₹${price.toFixed(2)} × ${quantity} = ₹${total.toFixed(2)}
                    </div>
                `;
            }
        } else if (formulaEl) {
            formulaEl.textContent = 'Price × Qty = Total';
        }
    }

    // Platform Checkbox Handlers - Initialize when DOM is ready
    function initializePlatformHandlers() {
        const platformCheckboxes = document.querySelectorAll('.platform-checkbox');
        
        console.log('Initializing platform handlers, found', platformCheckboxes.length, 'checkboxes');
        
        platformCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const platform = this.dataset.platform;
                const pricingCard = document.getElementById(`${platform}PricingCard`);
                
                console.log('Platform checkbox changed:', platform, 'Checked:', this.checked);
                
                if (this.checked) {
                    if (pricingCard) {
                        pricingCard.style.display = 'block';
                        console.log('Showing pricing card for:', platform);
                    }
                    if (platformPricingSection) {
                        platformPricingSection.style.display = 'block';
                    }
                    // Calculate initial values when platform is selected
                    setTimeout(() => {
                        if (typeof calculatePlatformPrice === 'function') {
                            calculatePlatformPrice(platform);
                        }
                    }, 100);
                } else {
                    if (pricingCard) {
                        pricingCard.style.display = 'none';
                    }
                    
                    // Hide pricing section if no platforms selected
                    const anyChecked = Array.from(platformCheckboxes).some(cb => cb.checked);
                    if (!anyChecked && platformPricingSection) {
                        platformPricingSection.style.display = 'none';
                    }
                }
            });
        });
    }
    
    // Initialize platform handlers
    initializePlatformHandlers();

    // Attach calculation listeners to all platform inputs
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('platform-calc-input')) {
            const platform = e.target.dataset.platform;
            if (platform) {
                calculatePlatformPrice(platform);
            }
        }
    });


    // Form Validation
    document.getElementById('pushProductForm').addEventListener('submit', function(e) {
        const productId = productSelect.value;
        const platformCheckboxes = document.querySelectorAll('.platform-checkbox');
        const platforms = Array.from(platformCheckboxes).filter(cb => cb.checked);
        
        if (!productId) {
            e.preventDefault();
            alert('Please select a product from inventory');
            return false;
        }
        
        if (platforms.length === 0) {
            e.preventDefault();
            alert('Please select at least one platform');
            return false;
        }
        
        // Validate platform pricing
        let isValid = true;
        platforms.forEach(checkbox => {
            const platform = checkbox.dataset.platform;
            const priceInput = document.querySelector(`input[name="platforms[${platform}][price]"]`);
            const qtyInput = document.querySelector(`input[name="platforms[${platform}][quantity]"]`);
            
            if (!priceInput.value || parseFloat(priceInput.value) <= 0) {
                isValid = false;
                alert(`Please enter a valid price for ${platform}`);
            }
            
            if (!qtyInput.value || parseInt(qtyInput.value) < 0) {
                isValid = false;
                alert(`Please enter a valid quantity for ${platform}`);
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endverbatim
