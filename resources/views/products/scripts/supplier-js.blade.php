<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
       SUPPLIER DETAILS (existing)
    =============================== */

    const supplierSelect = document.getElementById('supplierSelect');
    const supplierBox    = document.getElementById('supplierBox');

    if (supplierSelect && supplierBox) {

        const sName       = document.getElementById('sName');
        const sCompany    = document.getElementById('sCompany');
        const sPhone      = document.getElementById('sPhone');
        const sEmail      = document.getElementById('sEmail');
        const sType       = document.getElementById('sType');
        const sCommission = document.getElementById('sCommission');

        function updateSupplierDetails() {

            if (!supplierSelect.value) {
                supplierBox.classList.add('d-none');
                return;
            }

            const option = supplierSelect.options[supplierSelect.selectedIndex];

            sName.innerText       = option.dataset.name || '—';
            sCompany.innerText    = option.dataset.company || '—';
            sPhone.innerText      = option.dataset.phone || '—';
            sEmail.innerText      = option.dataset.email || '—';
            sType.innerText       = option.dataset.type || '—';
            sCommission.innerText = option.dataset.commission || '—';

            supplierBox.classList.remove('d-none');
        }

        supplierSelect.addEventListener('change', updateSupplierDetails);
        updateSupplierDetails();
    }

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const select = document.getElementById('paymentTermsSelect');
    const custom = document.getElementById('customPaymentDays');
    const hidden = document.getElementById('finalPaymentTerms');

    if (!select || !custom || !hidden) return;

    function syncPaymentTerms() {

        if (select.value === 'custom') {
            custom.classList.remove('d-none');

            if (custom.value) {
                hidden.value = 'net_' + custom.value;
            } else {
                hidden.value = '';
            }

        } else {
            custom.classList.add('d-none');
            custom.value = '';
            hidden.value = select.value;
        }
    }

    // dropdown change
    select.addEventListener('change', syncPaymentTerms);

    // typing days
    custom.addEventListener('input', function () {
        if (select.value === 'custom' && custom.value) {
            hidden.value = 'net_' + custom.value;
        }
    });

    // 🔥 VERY IMPORTANT: edit page load
    syncPaymentTerms();
});
</script>
