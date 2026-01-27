<script>
const supplierSelect = document.getElementById('supplierSelect');
const supplierBox = document.getElementById('supplierBox');

supplierSelect.onchange = () => {
    if (!supplierSelect.value) return supplierBox.classList.add('d-none');
    const o = supplierSelect.options[supplierSelect.selectedIndex];
    sName.innerText = o.dataset.name;
    sCompany.innerText = o.dataset.company;
    sPhone.innerText = o.dataset.phone;
    sEmail.innerText = o.dataset.email;
    sType.innerText = o.dataset.type;
    sCommission.innerText = o.dataset.commission;
    supplierBox.classList.remove('d-none');
};
</script>
