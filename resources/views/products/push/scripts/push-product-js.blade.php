@verbatim
<script>
document.addEventListener('DOMContentLoaded', () => {

    const productSelect = document.getElementById('productSelect');
    const productDetailsCard = document.getElementById('productDetailsCard');
    const variantsCard = document.getElementById('productVariantsCard');
    const variantsBody = document.getElementById('variantsTableBody');
    const platformCard = document.getElementById('platformSelectionCard');
    const pricingSection = document.getElementById('platformPricingSection');

    /* STEP 1 + 2 */
    productSelect.addEventListener('change', () => {

        const option = productSelect.options[productSelect.selectedIndex];
        if (!productSelect.value) {
            productDetailsCard.style.display = 'none';
            variantsCard.style.display = 'none';
            platformCard.style.display = 'none';
            pricingSection.style.display = 'none';
            return;
        }

        // Product details
        document.getElementById('displayCategory').innerText = option.dataset.category;
        document.getElementById('displaySubCategory').innerText = option.dataset.subcategory;
        document.getElementById('displaySku').innerText = option.dataset.sku;
        document.getElementById('displaySlug').innerText = option.dataset.slug;
        document.getElementById('displayBrand').innerText = option.dataset.brand;
        productDetailsCard.style.display = 'block';

        // Variants
        const variants = JSON.parse(option.dataset.variants || '[]');
        renderVariants(variants);
    });

    /* STEP 3 */
    function renderVariants(variants) {
        variantsBody.innerHTML = '';

        if (!variants.length) {
            variantsBody.innerHTML = `<tr><td colspan="6" class="text-center">No variants</td></tr>`;
            return;
        }

        variants.forEach(v => {
            variantsBody.innerHTML += `
                <tr class="text-center">
                    <td><input type="checkbox" class="variant-checkbox" value="${v.id}"></td>
                    <td>${v.variant_type}</td>
                    <td>${v.variant_value}</td>
                    <td>${v.sku_suffix ?? '-'}</td>
                    <td>${v.quantity ?? 0}</td>
                    <td>
                        <span class="badge ${v.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                            ${v.status}
                        </span>
                    </td>
                </tr>
            `;
        });

        variantsCard.style.display = 'block';
        bindVariantCheckbox();
    }

    /* STEP 4 */
    function bindVariantCheckbox() {
        document.querySelectorAll('.variant-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                const anyChecked = [...document.querySelectorAll('.variant-checkbox')]
                                    .some(c => c.checked);

                platformCard.style.display = anyChecked ? 'block' : 'none';
                if (!anyChecked) pricingSection.style.display = 'none';
            });
        });
    }

    /* STEP 5 + 6 */
    document.querySelectorAll('.platform-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const platform = cb.dataset.platform;
            const card = document.getElementById(platform + 'PricingCard');

            card.style.display = cb.checked ? 'block' : 'none';
            pricingSection.style.display =
                document.querySelectorAll('.platform-checkbox:checked').length
                ? 'block'
                : 'none';
        });
    });

});
</script>
@endverbatim
