document.addEventListener('DOMContentLoaded', function () {

    /* =========================
       SEARCH (AUTO SUBMIT)
    ========================== */
    const supplierSearch = document.getElementById('supplierSearch');
    const clearSupplierBtn = document.getElementById('clearSupplierSearch');
    const supplierForm = document.getElementById('supplierFilterForm');

    let supplierDebounce;

    function toggleSupplierClear() {
        if (clearSupplierBtn && supplierSearch) {
            clearSupplierBtn.style.display = supplierSearch.value ? 'block' : 'none';
        }
    }

    if (supplierSearch && supplierForm) {
        supplierSearch.addEventListener('input', function () {
            toggleSupplierClear();
            clearTimeout(supplierDebounce);
            supplierDebounce = setTimeout(() => {
                supplierForm.submit();
            }, 400);
        });
    }

    if (clearSupplierBtn) {
        clearSupplierBtn.addEventListener('click', function () {
            supplierSearch.value = '';
            toggleSupplierClear();
            supplierForm.submit();
        });
    }

    toggleSupplierClear();

    const supplierTypeSelect = supplierForm?.querySelector('select[name="type"]');
    if (supplierTypeSelect) {
        supplierTypeSelect.addEventListener('change', function () {
            supplierForm.submit();
        });
    }

    /* =========================
       FILTER SIDEBAR
    ========================== */
    const openSupplierBtn = document.getElementById('openSupplierFilterSidebar');
    const closeSupplierBtn = document.getElementById('closeSupplierFilterSidebar');
    const supplierSidebar = document.getElementById('supplierFilterSidebar');

    openSupplierBtn?.addEventListener('click', () => {
        supplierSidebar.classList.add('active');
    });

    closeSupplierBtn?.addEventListener('click', () => {
        supplierSidebar.classList.remove('active');
    });

    document.getElementById('applySupplierAdvancedFilter')
        ?.addEventListener('click', () => {

            const field = document.getElementById('advField').value;
            const condition = document.getElementById('advCondition').value;
            const value = document.getElementById('advValue').value;

            if (!value) return;

            const url = new URL(window.location.href);
            url.searchParams.set('adv_field', field);
            url.searchParams.set('adv_condition', condition);
            url.searchParams.set('adv_value', value);

            window.location.href = url.toString();
        });

    /* =========================
       SELECT ALL CHECKBOX
    ========================== */
    const selectAll = document.getElementById('selectAllSuppliers');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            const rowCheckboxes = document.querySelectorAll('.supplier-row-checkbox');
            rowCheckboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    }

    // auto update header checkbox when rows change
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('supplier-row-checkbox')) return;

        const all = document.querySelectorAll('.supplier-row-checkbox');
        const checked = document.querySelectorAll('.supplier-row-checkbox:checked');
        const selectAll = document.getElementById('selectAllSuppliers');

        if (selectAll) {
            selectAll.checked = all.length === checked.length;
        }
    });

    /* =========================
       BULK DELETE (FINAL & SAFE)
    ========================== */
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkDeleteForm = document.getElementById('supplierBulkDeleteForm');

    if (bulkDeleteBtn && bulkDeleteForm) {
        bulkDeleteBtn.addEventListener('click', function () {

            const checked = document.querySelectorAll('.supplier-row-checkbox:checked');

            if (checked.length === 0) {
                alert('Please select at least one supplier to delete.');
                return;
            }

            const confirmed = confirm(
                'Are you sure you want to delete ' + checked.length + ' supplier(s)?'
            );

            if (!confirmed) {
                return; // ❌ cancel = nothing happens
            }

            // ✅ confirmed → submit
            bulkDeleteForm.submit();
        });
    }

});
document.addEventListener('DOMContentLoaded', function () {

    const clearBtn = document.getElementById('clearSupplierAdvancedFilter');

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {

            // 1️⃣ Reset advanced filter inputs
            document.getElementById('advField').selectedIndex = 0;
            document.getElementById('advCondition').selectedIndex = 0;
            document.getElementById('advValue').value = '';

            // 2️⃣ Remove advanced filter params from URL
            const params = new URLSearchParams(window.location.search);
            params.delete('adv_field');
            params.delete('adv_condition');
            params.delete('adv_value');

            // 3️⃣ Reload page without advanced filters
            window.location = `?${params.toString()}`;
        });
    }

});
