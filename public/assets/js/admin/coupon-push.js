document.addEventListener('DOMContentLoaded', function () {

    const typeRadios = document.querySelectorAll('input[name="coupon_type"]');
    const bankFields = document.querySelectorAll('.bank-only');

    function toggleBankFields() {
        const selectedType = document.querySelector('input[name="coupon_type"]:checked')?.value;

        if (selectedType === 'BANK') {
            bankFields.forEach(el => el.classList.remove('d-none'));
        } else {
            bankFields.forEach(el => el.classList.add('d-none'));
        }
    }

    // initial load
    toggleBankFields();

    // on change
    typeRadios.forEach(radio => {
        radio.addEventListener('change', toggleBankFields);
    });

});
