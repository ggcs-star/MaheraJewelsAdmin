@verbatim
<script>
document.addEventListener('DOMContentLoaded', function () {

    const productSelect      = document.getElementById('productSelect');
    const productDetailsCard = document.getElementById('productDetailsCard');
    const variantsCard       = document.getElementById('productVariantsCard');
    const variantsBody       = document.getElementById('variantsTableBody');
    const form               = document.getElementById('pushProductForm');

    const modalEl            = document.getElementById('variantPlatformModal');
    const modal              = new bootstrap.Modal(modalEl);

    let activeVariantId = null;
    let variantPlatformData = {};

    // ---------------- PRODUCT CHANGE ----------------
    productSelect.addEventListener('change', function () {

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
                variantPlatformData[v.id]
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

                resetModalUI();
                loadVariantData(activeVariantId);

                document.getElementById('platformSelectionCard').style.display = 'block';
                document.getElementById('platformPricingSection').style.display = 'none';

                modal.show();
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
            document.getElementById('discount_' + platformId).value = values.discount;
            document.getElementById('quantity_' + platformId).value = values.quantity;
        });
    }

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
        });
    });

    // ---------------- SAVE VARIANT DATA ----------------
    document.getElementById('saveVariantData').addEventListener('click', function () {

        if (!activeVariantId) {
            alert('No variant selected');
            return;
        }

        variantPlatformData[activeVariantId] = {};

        document.querySelectorAll('.platform-checkbox:checked').forEach(cb => {

            const platformId = cb.dataset.platformId;

            variantPlatformData[activeVariantId][platformId] = {
                price:    document.getElementById('price_' + platformId).value,
                discount: document.getElementById('discount_' + platformId).value,
                quantity: document.getElementById('quantity_' + platformId).value,
            };
        });

        const row = document.querySelector(
            `.configure-variant-btn[data-variant-id="${activeVariantId}"]`
        ).closest('tr');

        row.querySelector('.variant-status').innerHTML =
            `<span class="badge bg-success">Configured</span>`;

        modal.hide();
        activeVariantId = null;
    });

    // ---------------- FORM SUBMIT ----------------
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

});
</script>
@endverbatim
