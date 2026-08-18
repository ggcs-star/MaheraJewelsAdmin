document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('filterForm');
    const search = document.getElementById('couponSearch');
    const clear = document.getElementById('clearSearch');

    if (search && clear && form) {
        const toggleClear = () => {
            clear.style.display = search.value ? 'block' : 'none';
        };

        toggleClear();

        let timer;
        search.addEventListener('input', () => {
            toggleClear();
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 400);
        });

        clear.onclick = () => {
            search.value = '';
            form.submit();
        };
    }

    document.querySelectorAll('.auto-submit').forEach(el => {
        el.onchange = () => el.form.submit();
    });

    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.onclick = e => {
            document.querySelectorAll('.row-checkbox')
                .forEach(cb => cb.checked = e.target.checked);
        };
    }

    const sidebar = document.getElementById('filterSidebar');
    const openSidebar = document.getElementById('openFilterSidebar');
    const closeSidebar = document.getElementById('closeFilterSidebar');

    if (openSidebar && sidebar) {
        openSidebar.onclick = () => sidebar.classList.add('open');
    }

    if (closeSidebar && sidebar) {
        closeSidebar.onclick = () => sidebar.classList.remove('open');
    }

    const advField = document.getElementById('advField');
    const advCondition = document.getElementById('advCondition');
    const advValue = document.getElementById('advValue');
    const applyFilter = document.getElementById('applyAdvancedFilter');

    if (applyFilter) {
        applyFilter.onclick = () => {
            const v = advValue.value.trim();
            if (!v) return;

            const params = new URLSearchParams(window.location.search);
            params.set('adv_field', advField.value);
            params.set('adv_condition', advCondition.value);
            params.set('adv_value', v);

            window.location = `?${params.toString()}`;
        };
    }

    const bulkBtn = document.getElementById('openBulkDeleteModal');
    const confirmBtn = document.getElementById('confirmBulkDelete');
    const bulkForm = document.getElementById('bulkDeleteForm');
    const modalEl = document.getElementById('bulkDeleteModal');
    const modalText = document.getElementById('deleteModalText');

    let activeSingleForm = null;

    if (bulkBtn) {
        bulkBtn.onclick = () => {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (!checked.length) {
                alert('Please select at least one coupon');
                return;
            }

            activeSingleForm = null;
            modalText.innerText =
                `Are you sure you want to delete ${checked.length} selected coupon(s)?`;

modalEl.classList.remove('hidden');
modalEl.classList.add('show');
        };
    }
document.querySelectorAll('.openSingleDeleteModal').forEach(btn => {
    btn.onclick = () => {
        activeSingleForm = btn.closest('.singleDeleteForm');
        activeSingleForm.action = btn.dataset.action;
        modalText.innerText = 'Are you sure you want to delete this coupon?';

        modalEl.classList.remove('hidden');
        modalEl.classList.add('show');
    };
});
    

    if (confirmBtn) {
        confirmBtn.onclick = () => {
            activeSingleForm ? activeSingleForm.submit() : bulkForm.submit();
        };
    }

});
document.addEventListener('DOMContentLoaded', () => {
const couponType = document.getElementById('coupon_type');
const bankField = document.querySelector('.bank-field');
const bankSelect = document.getElementById('bank_id');
const cardTypeSelect = document.getElementById('card_type');

if (couponType) {
    const toggleBankFields = () => {
        if (couponType.value === 'BANK') {
            bankField.classList.remove('d-none');

            bankSelect.setAttribute('required', true);
            cardTypeSelect.setAttribute('required', true);
        } else {
            bankField.classList.add('d-none');
            bankSelect.removeAttribute('required');
            cardTypeSelect.removeAttribute('required');
        }
    };

    couponType.addEventListener('change', toggleBankFields);
    toggleBankFields(); 
}


    document.querySelectorAll('input[name="platform_ids[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const box = this.nextElementSibling;
            const checkIcon = box.parentElement.querySelector('.check-icon');

            if (this.checked) {
                box.classList.add('border-primary', 'border-2');
                box.style.backgroundColor = '#f8f9ff';
                if (checkIcon) checkIcon.classList.remove('d-none');
            } else {
                box.classList.remove('border-primary', 'border-2');
                box.style.backgroundColor = '';
                if (checkIcon) checkIcon.classList.add('d-none');
            }
        });
    });

    const startDate = document.querySelector('input[name="starts_at"]');
    if (startDate && !startDate.value) {
        startDate.valueAsDate = new Date();
    }
});
// const clearAdvancedBtn = document.getElementById('clearAdvancedFilter');

// if (clearAdvancedBtn) {
//     clearAdvancedBtn.onclick = () => {
//         advField.selectedIndex = 0;
//         advCondition.selectedIndex = 0;
//         advValue.value = '';
//         const params = new URLSearchParams(window.location.search);
//         params.delete('adv_field');
//         params.delete('adv_condition');
//         params.delete('adv_value');

//         window.location = `?${params.toString()}`;
//     };
// }
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('filterForm');
    const search = document.getElementById('couponSearch');
    const clear = document.getElementById('clearSearch');

    if (search && clear && form) {
        const toggleClear = () => {
            clear.style.display = search.value ? 'block' : 'none';
        };

        toggleClear();

        let timer;
        search.addEventListener('input', () => {
            toggleClear();
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 400);
        });

        clear.onclick = () => {
            search.value = '';
            form.submit();
        };
    }

    document.querySelectorAll('.auto-submit').forEach(el => {
        el.onchange = () => el.form.submit();
    });

    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.onclick = e => {
            document.querySelectorAll('.row-checkbox')
                .forEach(cb => cb.checked = e.target.checked);
        };
    }

    const sidebar = document.getElementById('filterSidebar');
    const openSidebar = document.getElementById('openFilterSidebar');
    const closeSidebar = document.getElementById('closeFilterSidebar');

    if (openSidebar && sidebar) {
        openSidebar.onclick = () => sidebar.classList.add('open');
    }

    if (closeSidebar && sidebar) {
        closeSidebar.onclick = () => sidebar.classList.remove('open');
    }

    const advField = document.getElementById('advField');
    const advCondition = document.getElementById('advCondition');
    const advValue = document.getElementById('advValue');
    const applyFilter = document.getElementById('applyAdvancedFilter');

    if (applyFilter) {
        applyFilter.onclick = () => {
            const v = advValue.value.trim();
            if (!v) return;

            const params = new URLSearchParams(window.location.search);
            params.set('adv_field', advField.value);
            params.set('adv_condition', advCondition.value);
            params.set('adv_value', v);

            window.location = `?${params.toString()}`;
        };
    }

    const bulkBtn = document.getElementById('openBulkDeleteModal');
    const confirmBtn = document.getElementById('confirmBulkDelete');
    const bulkForm = document.getElementById('bulkDeleteForm');
    const modalEl = document.getElementById('bulkDeleteModal');
    const modalText = document.getElementById('deleteModalText');
    const closeButtons = modalEl.querySelectorAll('[data-bs-dismiss="modal"]');

closeButtons.forEach(btn => {
    modalEl.addEventListener('click', function (e) {
    if (e.target === modalEl) {
        modalEl.classList.remove('show');
        modalEl.classList.add('hidden');
    }
});
    btn.onclick = () => {
        modalEl.classList.remove('show');
        modalEl.classList.add('hidden');
    };
});

    let activeSingleForm = null;

    if (bulkBtn) {
        bulkBtn.onclick = () => {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (!checked.length) {
                alert('Please select at least one coupon');
                return;
            }

            activeSingleForm = null;
            modalText.innerText =
                `Are you sure you want to delete ${checked.length} selected coupon(s)?`;

modalEl.classList.remove('hidden');
modalEl.classList.add('show');        };
    }

   document.querySelectorAll('.openSingleDeleteModal').forEach(btn => {
    btn.onclick = () => {
        activeSingleForm = btn.closest('.singleDeleteForm');
        activeSingleForm.action = btn.dataset.action;
        modalText.innerText = 'Are you sure you want to delete this coupon?';

        modalEl.classList.remove('hidden');
        modalEl.classList.add('show');
    };
});

    if (confirmBtn) {
        confirmBtn.onclick = () => {
            activeSingleForm ? activeSingleForm.submit() : bulkForm.submit();
        };
    }

});
document.addEventListener('DOMContentLoaded', () => {
const couponType = document.getElementById('coupon_type');
const bankField = document.querySelector('.bank-field');
const bankSelect = document.getElementById('bank_id');
const cardTypeSelect = document.getElementById('card_type');

if (couponType) {
    const toggleBankFields = () => {
        if (couponType.value === 'BANK') {
            bankField.classList.remove('d-none');

            bankSelect.setAttribute('required', true);
            cardTypeSelect.setAttribute('required', true);
        } else {
            bankField.classList.add('d-none');
            bankSelect.removeAttribute('required');
            cardTypeSelect.removeAttribute('required');
        }
    };

    couponType.addEventListener('change', toggleBankFields);
    toggleBankFields(); 
}


    document.querySelectorAll('input[name="platform_ids[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const box = this.nextElementSibling;
            const checkIcon = box.parentElement.querySelector('.check-icon');

            if (this.checked) {
                box.classList.add('border-primary', 'border-2');
                box.style.backgroundColor = '#f8f9ff';
                if (checkIcon) checkIcon.classList.remove('d-none');
            } else {
                box.classList.remove('border-primary', 'border-2');
                box.style.backgroundColor = '';
                if (checkIcon) checkIcon.classList.add('d-none');
            }
        });
    });

    const startDate = document.querySelector('input[name="starts_at"]');
    if (startDate && !startDate.value) {
        startDate.valueAsDate = new Date();
    }
});
const clearAdvancedBtn = document.getElementById('clearAdvancedFilter');

if (clearAdvancedBtn) {
    clearAdvancedBtn.onclick = () => {
        advField.selectedIndex = 0;
        advCondition.selectedIndex = 0;
        advValue.value = '';
        const params = new URLSearchParams(window.location.search);
        params.delete('adv_field');
        params.delete('adv_condition');
        params.delete('adv_value');

        window.location = `?${params.toString()}`;
    };
}
// ===============================
// Coupon Details Column Click
// ===============================
document.addEventListener('click', function (e) {

    const cell = e.target.closest('.coupon-clickable');
    if (!cell) return;

    // Safety: ignore buttons, links, inputs
    if (
        e.target.closest('button') ||
        e.target.closest('a') ||
        e.target.closest('input')
    ) {
        return;
    }

    const url = cell.dataset.url;
    if (url) {
        window.location.href = url;
    }
});
// ======================================
// Category -> Sub Category -> Product
// ======================================

document.addEventListener('DOMContentLoaded', function () {

    const category = document.getElementById('category_id');
    const subCategory = document.getElementById('subcategory_id');
    const product = document.getElementById('product_id');

    if (!category || !subCategory || !product) return;

    // Load Sub Categories
    category.addEventListener('change', function () {

        const id = this.value;

        subCategory.innerHTML = '<option value="">Loading...</option>';
        product.innerHTML = '<option value="">Select Product</option>';

        if (!id) {
            subCategory.innerHTML = '<option value="">Select Sub Category</option>';
            return;
        }

        fetch('/admin/coupons/subcategories/' + id)
            .then(response => response.json())
            .then(data => {

                subCategory.innerHTML = '<option value="">Select Sub Category</option>';

                data.forEach(function (item) {

                    subCategory.innerHTML +=
                        `<option value="${item.id}">
                            ${item.name}
                        </option>`;

                });

            });

    });

    // Load Products
    subCategory.addEventListener('change', function () {

        const id = this.value;

        product.innerHTML = '<option value="">Loading...</option>';

        if (!id) {

            product.innerHTML = '<option value="">Select Product</option>';

            return;
        }

        fetch('/admin/coupons/products/' + id)
            .then(response => response.json())
            .then(data => {

                product.innerHTML = '<option value="">Select Product</option>';

                data.forEach(function (item) {

                    product.innerHTML +=
                        `<option value="${item.id}">
                            ${item.name}
                        </option>`;

                });

            });

    });

});
document.addEventListener('DOMContentLoaded', function () {

    const generateType = document.getElementById('generate_type');
    const couponCodeField = document.getElementById('coupon_code_field');
    const bulkFields = document.getElementById('bulk_fields');

    const campaign = document.querySelector('[name="campaign_name"]');
    const prefix = document.querySelector('[name="prefix"]');
    const quantity = document.querySelector('[name="quantity"]');

    if (!generateType) return;

    function toggleFields() {

        if (generateType.value === 'bulk') {

            couponCodeField.style.display = 'none';
            bulkFields.style.display = 'block';

            campaign.removeAttribute('disabled');
            prefix.removeAttribute('disabled');
            quantity.removeAttribute('disabled');

        } else {

            couponCodeField.style.display = 'block';
            bulkFields.style.display = 'none';

            campaign.value = '';
            prefix.value = '';
            quantity.value = '';

            campaign.setAttribute('disabled', true);
            prefix.setAttribute('disabled', true);
            quantity.setAttribute('disabled', true);
        }
    }

    generateType.addEventListener('change', toggleFields);

    toggleFields();
});
// ==========================================
// Category Filter Auto-Submit (Main + Sidebar Sync)
// ==========================================
const categoryFilterMain = document.getElementById('filterCategoryMain');
const categoryFilterSidebar = document.getElementById('filterCategory');

if (categoryFilterMain) {
    categoryFilterMain.addEventListener('change', function() {
        if (categoryFilterSidebar) {
            categoryFilterSidebar.value = this.value;
        }
        const form = document.getElementById('filterForm');
        if (form) {
            form.submit();
        }
    });
}

if (categoryFilterSidebar) {
    categoryFilterSidebar.addEventListener('change', function() {
        if (categoryFilterMain) {
            categoryFilterMain.value = this.value;
        }
        const form = document.getElementById('filterForm');
        if (form) {
            form.submit();
        }
    });
}
