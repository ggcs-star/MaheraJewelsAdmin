@verbatim
<script>
document.addEventListener('DOMContentLoaded', function () {

    const productSelect      = document.getElementById('productSelect');
    const productDetailsCard = document.getElementById('productDetailsCard');
    const variantsCard       = document.getElementById('productVariantsCard');
    const variantsBody       = document.getElementById('variantsTableBody');
    const form               = document.getElementById('pushProductForm');
    const previewBtn         = document.getElementById('previewPushBtn');
    const previewBody        = document.getElementById('pushPreviewBody');
    const confirmBtn         = document.getElementById('confirmPushBtn');

    const modalEl   = document.getElementById('variantPlatformModal');
    const previewEl = document.getElementById('pushPreviewModal');

    let previewModal = null;
    let modal = null;

    if (window.bootstrap) {
        if (previewEl) previewModal = new bootstrap.Modal(previewEl);
        if (modalEl) modal = new bootstrap.Modal(modalEl);
    }
let activeVariantId = null;
let variantPlatformData = {};


if (window.existingVariantPlatformData && Object.keys(window.existingVariantPlatformData).length) {
    localStorage.removeItem('variantPlatformData');
}


let saved = localStorage.getItem('variantPlatformData');

if (saved) {
    let draft = JSON.parse(saved);

    if (draft._productId && draft._productId == document.getElementById('selectedProductId').value) {
        variantPlatformData = draft.data || {};
    }
}




    if (productSelect.value) {
    document.getElementById('selectedProductId').value = productSelect.value;
}
    form.addEventListener('submit', function () {
        let input = document.getElementById('variantPlatformDataInput');

        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'variant_platform_data';
            input.id = 'variantPlatformDataInput';
            form.appendChild(input);
        }

        input.value = JSON.stringify(variantPlatformData);
    });


        // ---------------- PRODUCT CHANGE ----------------
productSelect.addEventListener('change', function () {
    document.getElementById('selectedProductId').value = this.value;


    const option = this.options[this.selectedIndex];

    if (!this.value) {
        productDetailsCard.style.display = 'none';
        variantsCard.style.display = 'none';
        return;
    }

    document.getElementById('displayCategory').innerText    = option.dataset.category || '-';
    document.getElementById('displaySubCategory').innerText = option.dataset.subcategory || '-';
    document.getElementById('displaySku').innerText         = option.dataset.sku || '-';
    document.getElementById('displaySlug').innerText        = option.dataset.slug || '-';
    document.getElementById('displayBrand').innerText       = option.dataset.brand || '-';

    productDetailsCard.style.display = 'block';

    let variants = [];
    try {
        variants = JSON.parse(option.dataset.variants || '[]');
    } catch (e) {
        console.error('Variant JSON error', e);
    }

    renderVariants(variants);


});



        // ---------------- RENDER VARIANTS ----------------
        function renderVariants(variants) {

            variantsBody.innerHTML = '';

            if (!variants.length) {
                variantsBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No variants found
                        </td>
                    </tr>`;
                return;
            }

            variants.forEach(v => {

            const statusBadge =
    variantPlatformData[v.id] && Object.keys(variantPlatformData[v.id]).length
        ? `<span class="badge bg-success">Configured</span>`
        : `<span class="badge bg-secondary">Not Configured</span>`;


                variantsBody.insertAdjacentHTML('beforeend', `
                    <tr class="text-center">
                        <td>
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary configure-variant-btn"
                                    data-variant-id="${v.id}">
                                ⚙️ Configure
                            </button>
                        </td>
                        <td>${v.variant_type}</td>
                        <td>${v.variant_value}</td>
                        <td>${v.sku_suffix ?? '-'}</td>
                        <td>${v.quantity ?? 0}</td>
                        <td class="variant-status">${statusBadge}</td>
                    </tr>
                `);
            });

            variantsCard.style.display = 'block';
            bindConfigureButtons();
        }

        // ---------------- CONFIGURE BUTTON ----------------
function bindConfigureButtons() {
    document.querySelectorAll('.configure-variant-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            activeVariantId = this.dataset.variantId;
            const row = this.closest('tr');

            let originalStock = parseInt(row.children[4].innerText) || 0;
            window.variantOriginalStock = originalStock;

            resetModalUI();

        document.getElementById('platformSelectionCard').style.display = 'block';

loadVariantData(activeVariantId); 

const data = variantPlatformData[activeVariantId];
document.getElementById('platformPricingSection').style.display =
    data && Object.keys(data).length ? 'block' : 'none';


            if (modal) modal.show();

            setTimeout(updateSharedStockDisplay, 200);
        });
    });
}


        // ---------------- RESET MODAL ----------------
        function resetModalUI() {
            document.querySelectorAll('.platform-checkbox')
                .forEach(cb => cb.checked = false);

            document.querySelectorAll('.platform-pricing-card')
                .forEach(card => card.style.display = 'none');
        }

        // ---------------- LOAD EXISTING DATA ----------------
        function loadVariantData(variantId) {

            const data = variantPlatformData[variantId];
            document.getElementById('platformPricingSection').style.display =
                data ? 'block' : 'none';

            if (!data) return;

            Object.entries(data).forEach(([platformId, values]) => {

                const checkbox = document.querySelector(
                    `.platform-checkbox[data-platform-id="${platformId}"]`
                );
                if (!checkbox) return;

                checkbox.checked = true;

                document.getElementById('platformPricingCard_' + platformId).style.display = 'block';

                document.getElementById('price_' + platformId).value    = values.price;
    document.getElementById('discount_value_' + platformId).value = values.discount_value ?? 0;
    document.getElementById('discount_type_' + platformId).value  = values.discount_type ?? 'amount';

    document.getElementById('quantity_' + platformId).value = values.qty;

    calculateFinalTotal(platformId); 

            });
        }
        function updateSharedStockDisplay() {

    let totalUsed = 0;

    document.querySelectorAll('.platform-qty-input').forEach(input => {
        totalUsed += parseInt(input.value) || 0;
    });

    let master = window.variantOriginalStock || 0;
    let remaining = master - totalUsed;

    if (remaining < 0) remaining = 0;

    document.querySelectorAll('.stock-display').forEach(box => {
        box.querySelector('.available-stock').innerText = master;
        box.querySelector('.remaining-stock').innerText = remaining;

        if (remaining <= 2) {
            box.querySelector('.remaining-stock').classList.add('text-danger');
        } else {
            box.querySelector('.remaining-stock').classList.remove('text-danger');
        }
    });
}

    // -------- STOCK DISTRIBUTION CHECK --------
document.addEventListener('input', function(e) {

    if (!e.target.classList.contains('platform-qty-input')) return;

    let master = window.variantOriginalStock || 0;

    let totalUsed = 0;
    document.querySelectorAll('.platform-qty-input').forEach(input => {
        totalUsed += parseInt(input.value) || 0;
    });

    if (totalUsed > master) {
        alert("Total platform quantity exceeds available stock (" + master + ")");
        e.target.value = 0;
        updateSharedStockDisplay();
        return;
    }

    updateSharedStockDisplay();
});

        // ---------------- CHECKBOX CHANGE ----------------
   document.querySelectorAll('.platform-checkbox').forEach(cb => {

    cb.addEventListener('change', function () {

        const platformId = this.dataset.platformId;
        const card = document.getElementById('platformPricingCard_' + platformId);

        if (!card) return;

        card.style.display = this.checked ? 'block' : 'none';

        const anyChecked =
            document.querySelectorAll('.platform-checkbox:checked').length > 0;

        document.getElementById('platformPricingSection').style.display =
            anyChecked ? 'block' : 'none';

        setTimeout(updateSharedStockDisplay, 150);
    });
});

        // ---------------- SAVE VARIANT DATA ----------------
        document.getElementById('saveVariantData').addEventListener('click', function () {

            if (!activeVariantId) {
                alert('No variant selected');
                return;
            }


variantPlatformData[activeVariantId] = {}; // RESET old platform data

            document.querySelectorAll('.platform-checkbox:checked').forEach(cb => {

                const platformId = cb.dataset.platformId;
    let calc = calculateFinalTotal(platformId);

    variantPlatformData[activeVariantId][platformId] = calc;


            });
    let totalQty = 0;

    document.querySelectorAll('.platform-checkbox:checked').forEach(cb => {
        const platformId = cb.dataset.platformId;
        let qty = parseInt(document.getElementById('quantity_' + platformId).value) || 0;
        totalQty += qty;
    });

    if (totalQty > window.variantOriginalStock) {
        alert("Total platform quantity exceeds variant stock (" + window.variantOriginalStock + ")");
        return;
    }



            const row = document.querySelector(
                `.configure-variant-btn[data-variant-id="${activeVariantId}"]`
            ).closest('tr');

            row.querySelector('.variant-status').innerHTML =
                `<span class="badge bg-success">Configured</span>`;

            modal.hide();
            activeVariantId = null;
localStorage.setItem('variantPlatformData', JSON.stringify({
    _productId: document.getElementById('selectedProductId').value,
    data: variantPlatformData
}));




        });
    // ---------------- PRICE CALCULATION ----------------
    function calculateFinalTotal(platformId) {

        let price  = parseFloat(document.getElementById('price_' + platformId)?.value) || 0;
        let qty    = parseFloat(document.getElementById('quantity_' + platformId)?.value) || 0;
        let total  = price * qty;

        let discountValue = parseFloat(document.getElementById('discount_value_' + platformId)?.value) || 0;
        let discountType  = document.getElementById('discount_type_' + platformId)?.value;

        let discountAmount = 0;

        if (discountType === 'percent') {
            discountAmount = total * (discountValue / 100);
        } else {
            discountAmount = discountValue;
        }

        // Safety cap
        if (discountAmount > total) {
            discountAmount = total;
        }

        let finalTotal = total - discountAmount;

        document.getElementById('final_total_' + platformId).value = finalTotal.toFixed(2);

        return {
            price,
            qty,
            discount_value: discountValue,
            discount_type: discountType,
            discount_amount: discountAmount,
            final_total: finalTotal
        };
    }

    document.addEventListener('input', function(e) {

        let id = e.target.id;

        if (id.startsWith('price_') ||
            id.startsWith('quantity_') ||
            id.startsWith('discount_value_') ||
            id.startsWith('discount_type_')) {

            let platformId = id.split('_').pop();
            calculateFinalTotal(platformId);
        }

    });
    // ---------------- SAFE PREVIEW HANDLING ----------------
    if (previewBtn && previewBody) {

        previewBtn.addEventListener('click', function () {
    if (activeVariantId) {
     

        document.querySelectorAll('.platform-checkbox:checked').forEach(cb => {
            const platformId = cb.dataset.platformId;
            variantPlatformData[activeVariantId][platformId] = calculateFinalTotal(platformId);
        });
    }
            previewBody.innerHTML = '';
            let hasData = false;

            Object.keys(variantPlatformData).forEach(variantId => {
                let seen = new Set();


                const platforms = variantPlatformData[variantId];

                Object.keys(platforms).forEach(platformId => {

                    const p = platforms[platformId];
                    let qty = parseInt(p.qty) || 0;
                    if (!qty) return;

                    let key = variantId + '_' + platformId;
if (seen.has(key)) return;
seen.add(key);

                    hasData = true;

                    let discountText =
                        p.discount_type === 'percent'
                            ? p.discount_value + '%'
                            : '₹' + p.discount_value;

                    previewBody.insertAdjacentHTML('beforeend', `
                        <tr>
<td>${window.platformMap && window.platformMap[platformId] ? window.platformMap[platformId] : 'Platform ' + platformId}</td>
                            <td>${qty}</td>
                            <td>₹${p.price}</td>
                            <td>${discountText}</td>
                            <td class="fw-bold text-success">₹${p.final_total.toFixed(2)}</td>
                        </tr>
                    `);

                });
            });

            if (!hasData) {
                alert('No platform data configured!');
                return;
            }

    if (previewModal) previewModal.show();
        });

        if (confirmBtn) {
confirmBtn.addEventListener('click', function () {

    let input = document.getElementById('variantPlatformDataInput');

    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'variant_platform_data';
        input.id = 'variantPlatformDataInput';
        form.appendChild(input);
    }

    input.value = JSON.stringify(variantPlatformData);

    previewModal.hide();


    form.submit();

    setTimeout(() => {
        localStorage.removeItem('variantPlatformData');
variantPlatformData = window.existingVariantPlatformData || {};
    }, 500);
});




        }
    }
        

if (productSelect && productSelect.value) {

    const option = productSelect.options[productSelect.selectedIndex];

    if (option) {
        try {
            let variants = JSON.parse(option.dataset.variants || '[]');
            renderVariants(variants);
        } catch (e) {
            console.error('Auto-load variant JSON error', e);
        }
    }
}

});







    </script>
    @endverbatim
