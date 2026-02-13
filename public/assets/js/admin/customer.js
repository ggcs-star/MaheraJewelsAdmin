// customer.js - COMPLETE WORKING VERSION
document.addEventListener('DOMContentLoaded', function() {
    console.log('Customer JS loaded');
    
    let deleteUrl = null;
    let deleteType = null;

    /* ================= CLICKABLE ROW ================= */
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'BUTTON' || e.target.tagName === 'A') {
                return;
            }
            window.location.href = row.dataset.url;
        });
    });

    /* ================= SINGLE DELETE ================= */
    document.querySelectorAll('.openSingleDelete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            deleteUrl = btn.dataset.url;
            deleteType = 'single';
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });

    /* ================= BULK DELETE ================= */
    document.getElementById('openBulkDelete')?.addEventListener('click', () => {
        if (!document.querySelector('.row-checkbox:checked')) {
            alert('Please select at least one record');
            return;
        }

        deleteType = 'bulk';
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });

    /* ================= CONFIRM DELETE ================= */
    document.getElementById('confirmDelete')?.addEventListener('click', () => {
        if (deleteType === 'bulk') {
            document.getElementById('bulkDeleteForm').submit();
            return;
        }

        if (!deleteUrl) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;

        form.innerHTML = `
            <input type="hidden" name="_token"
                   value="${document.querySelector('meta[name=csrf-token]').content}">
            <input type="hidden" name="_method" value="DELETE">
        `;

        document.body.appendChild(form);
        form.submit();
    });

    /* ================= SELECT ALL ================= */
    document.getElementById('selectAll')?.addEventListener('change', e => {
        document.querySelectorAll('.row-checkbox')
            .forEach(cb => cb.checked = e.target.checked);
    });

    /* ================= AUTO SEARCH ================= */
    let timer;
    document.getElementById('customerSearch')?.addEventListener('keyup', e => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const url = new URL(window.location.href);
            url.searchParams.set('search', e.target.value);
            window.location.href = url.toString();
        }, 600);
    });

    /* ================= STATUS FILTER ================= */
    document.getElementById('statusFilter')?.addEventListener('change', e => {
        const url = new URL(window.location.href);
        url.searchParams.set('status', e.target.value);
        window.location.href = url.toString();
    });

    /* ================= ADVANCED FILTER ================= */
    const advField = document.getElementById('advField');
    const advCondition = document.getElementById('advCondition');
    const advValue = document.getElementById('advValue');
    const filterSidebar = document.getElementById('filterSidebar');
    const filterOverlay = document.getElementById('filterOverlay');
    const openAdvancedFilterBtn = document.getElementById('openAdvancedFilter');
    const closeAdvancedFilterBtn = document.getElementById('closeAdvancedFilter');
    const applyAdvancedFilterBtn = document.getElementById('applyAdvancedFilter');
    const clearAdvancedFilterBtn = document.getElementById('clearAdvancedFilter');

    // Function to open sidebar
    function openSidebar() {
        console.log('Opening sidebar');
        if (filterSidebar) {
            filterSidebar.style.display = 'block';
            if (filterOverlay) {
                filterOverlay.style.display = 'block';
            }
            // Force reflow
            filterSidebar.offsetHeight;
            filterSidebar.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    // Function to close sidebar
    function closeSidebar() {
        console.log('Closing sidebar');
        if (filterSidebar) {
            filterSidebar.classList.remove('show');
            if (filterOverlay) {
                filterOverlay.style.display = 'none';
            }
            setTimeout(() => {
                filterSidebar.style.display = 'none';
            }, 300);
            document.body.style.overflow = '';
        }
    }

    // Open sidebar
    if (openAdvancedFilterBtn) {
        openAdvancedFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openSidebar();
        });
    }

    // Close sidebar
    if (closeAdvancedFilterBtn) {
        closeAdvancedFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
        });
    }

    // Close sidebar when clicking on overlay
    if (filterOverlay) {
        filterOverlay.addEventListener('click', closeSidebar);
    }

    // Close with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && filterSidebar && filterSidebar.classList.contains('show')) {
            closeSidebar();
        }
    });

    // Apply filter
    if (applyAdvancedFilterBtn) {
        applyAdvancedFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (!advValue.value.trim()) {
                alert('Please enter filter value');
                return;
            }

            // Create URL with advanced filter params
            const url = new URL(window.location.href);
            url.searchParams.set('adv_field', advField.value);
            url.searchParams.set('adv_condition', advCondition.value);
            url.searchParams.set('adv_value', advValue.value.trim());

            closeSidebar();
            window.location.href = url.toString();
        });
    }

    // Clear filter
    if (clearAdvancedFilterBtn) {
        clearAdvancedFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Create URL without advanced filter params
            const url = new URL(window.location.href);
            url.searchParams.delete('adv_field');
            url.searchParams.delete('adv_condition');
            url.searchParams.delete('adv_value');
            
            closeSidebar();
            window.location.href = url.toString();
        });
    }

    // Set current values from URL
    const urlParams = new URLSearchParams(window.location.search);
    
    if (advField && urlParams.has('adv_field')) {
        advField.value = urlParams.get('adv_field');
    }
    
    if (advCondition && urlParams.has('adv_condition')) {
        advCondition.value = urlParams.get('adv_condition');
    }
    
    if (advValue && urlParams.has('adv_value')) {
        advValue.value = urlParams.get('adv_value');
    }

    // Enter key to apply filter
    if (advValue) {
        advValue.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && applyAdvancedFilterBtn) {
                e.preventDefault();
                applyAdvancedFilterBtn.click();
            }
        });
    }
});