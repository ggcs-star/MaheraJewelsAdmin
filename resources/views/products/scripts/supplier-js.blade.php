<script>
document.addEventListener('DOMContentLoaded', function () {

    const supplierSelect = document.getElementById('supplierSelect');
    const supplierBox    = document.getElementById('supplierBox');

    if (!supplierSelect || !supplierBox) return;

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
});
</script>
