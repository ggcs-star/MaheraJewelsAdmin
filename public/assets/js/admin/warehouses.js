document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('warehouseFilterForm');
    const search = document.getElementById('warehouseSearch');
    const city = document.getElementById('warehouseCity');
    const clearBtn = document.getElementById('clearWarehouseSearch');

    let debounce;

    function autoSubmit() {
        if (!form) return;
        clearTimeout(debounce);
        debounce = setTimeout(() => form.submit(), 400);
    }

    // 🔍 AUTO SEARCH
    search?.addEventListener('input', () => {
        if (clearBtn) {
            clearBtn.style.display = search.value ? 'block' : 'none';
        }
        autoSubmit();
    });

    // ⛔ PREVENT ENTER
    search?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    // 🏙️ CITY FILTER
    city?.addEventListener('change', autoSubmit);

    // ❌ CLEAR SEARCH
    clearBtn?.addEventListener('click', () => {
        search.value = '';
        clearBtn.style.display = 'none';
        form.submit();
    });

    // ✅ SHOW CLEAR ICON ON LOAD
    if (search && search.value.trim() !== '') {
        clearBtn.style.display = 'block';
    }

    // 🧠 ADVANCED FILTER
    document.getElementById('applyWarehouseAdvancedFilter')
        ?.addEventListener('click', () => {

        const params = new URLSearchParams(window.location.search);

        params.set('adv_field', document.getElementById('advField')?.value ?? '');
        params.set('adv_condition', document.getElementById('advCondition')?.value ?? '');
        params.set('adv_value', document.getElementById('advValue')?.value ?? '');

        window.location.search = params.toString();
    });

    // 🔽 OPEN FILTER SIDEBAR
    document.getElementById('openWarehouseFilterSidebar')
        ?.addEventListener('click', () => {
            document.getElementById('warehouseFilterSidebar')
                ?.classList.add('open');
        });

    // ❌ CLOSE FILTER SIDEBAR
    document.getElementById('closeWarehouseFilterSidebar')
        ?.addEventListener('click', () => {
            document.getElementById('warehouseFilterSidebar')
                ?.classList.remove('open');
        });

    // ================================
    // ✅ SELECT ALL / DESELECT ALL
    // ================================
    const selectAll = document.getElementById('selectAllWarehouses');
    const rowCheckboxes = document.querySelectorAll('.warehouse-row-checkbox');

    selectAll?.addEventListener('change', function () {
        rowCheckboxes.forEach(cb => cb.checked = this.checked);
    });

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const allChecked = [...rowCheckboxes].every(c => c.checked);
            const anyChecked = [...rowCheckboxes].some(c => c.checked);

            selectAll.checked = allChecked;
            selectAll.indeterminate = !allChecked && anyChecked;
        });
    });
    // ================================
// BULK DELETE SUBMIT
// ================================
const bulkDeleteBtn = document.getElementById('bulkDeleteWarehouseBtn');
const bulkDeleteForm = document.getElementById('warehouseBulkDeleteForm');

bulkDeleteBtn?.addEventListener('click', () => {

    const checked = document.querySelectorAll('.warehouse-row-checkbox:checked');

    if (checked.length === 0) {
        alert('Please select at least one warehouse to delete.');
        return;
    }

    if (confirm(`Delete ${checked.length} selected warehouse(s)?`)) {
        bulkDeleteForm.submit();
    }
});
// ================================
// AUTO HIDE FLASH MESSAGE
// ================================
const flashMessage = document.getElementById('flashMessage');

if (flashMessage) {
    setTimeout(() => {
        flashMessage.style.transition = 'opacity 0.5s ease';
        flashMessage.style.opacity = '0';

        setTimeout(() => flashMessage.remove(), 500);
    }, 3000); // 3 seconds
}


});
