// organization.js - SIMPLE VERSION
document.addEventListener('DOMContentLoaded', function() {
    console.log('Organization JS loaded');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    // ================= DEBUG =================
    console.log('Debug - Elements check:');
    console.log('openAdvancedFilter:', document.getElementById('openAdvancedFilter'));
    console.log('filterSidebar:', document.getElementById('filterSidebar'));
    console.log('orgSearch:', document.getElementById('orgSearch'));
    console.log('clearSearch:', document.getElementById('clearSearch'));

    // ================= SEARCH FUNCTIONALITY =================
    const filterForm = document.getElementById('filterForm');
    const orgSearch = document.getElementById('orgSearch');
    const clearSearch = document.getElementById('clearSearch');

    // Search with debounce
    if (orgSearch) {
        console.log('Search input found');
        let searchTimer;
        
        orgSearch.addEventListener('input', function() {
            console.log('Search input changed:', this.value);
            
            // Show/hide clear button
            if (clearSearch) {
                clearSearch.style.display = this.value ? 'block' : 'none';
            }
            
            // Debounce search
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                console.log('Submitting form after debounce');
                if (filterForm) {
                    filterForm.submit();
                }
            }, 400);
        });
    }

    // Clear search button
    if (clearSearch) {
        console.log('Clear button found');
        clearSearch.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Clear button clicked');
            
            if (orgSearch) {
                orgSearch.value = '';
                this.style.display = 'none';
                
                // Create a new URL without search parameter
                const url = new URL(window.location);
                url.searchParams.delete('search');
                window.location.href = url.toString();
            }
        });
    }

    // ================= AUTO SUBMIT =================
    document.querySelectorAll('.auto-submit').forEach(el => {
        el.addEventListener('change', function() {
            console.log('Auto submit changed:', this.name, this.value);
            if (this.form) {
                this.form.submit();
            }
        });
    });

    // ================= SELECT ALL CHECKBOXES =================
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        console.log('Select all found');
        selectAll.addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = e.target.checked;
            });
        });
    }

    // ================= ADVANCED FILTER SIDEBAR =================
    const openAdvancedFilter = document.getElementById('openAdvancedFilter');
    const filterSidebar = document.getElementById('filterSidebar');
    
    console.log('Advanced Filter Button:', openAdvancedFilter);
    console.log('Filter Sidebar:', filterSidebar);

    // Open sidebar
    if (openAdvancedFilter) {
        openAdvancedFilter.addEventListener('click', function(e) {
            console.log('Advanced filter button clicked');
            e.preventDefault();
            
            if (filterSidebar) {
                console.log('Opening sidebar');
                filterSidebar.style.right = '0';
                filterSidebar.classList.add('show');
            } else {
                console.log('Sidebar not found!');
            }
        });
    }

    // Close sidebar - add close button dynamically if not exists
    const closeAdvancedFilter = document.getElementById('closeAdvancedFilter');
    if (!closeAdvancedFilter && filterSidebar) {
        console.log('Creating close button');
        const header = filterSidebar.querySelector('.filter-header');
        if (header) {
            const closeBtn = document.createElement('button');
            closeBtn.id = 'closeAdvancedFilter';
            closeBtn.className = 'btn btn-sm btn-link text-muted';
            closeBtn.innerHTML = '<i class="fas fa-times fa-lg"></i>';
            closeBtn.style.marginLeft = 'auto';
            header.querySelector('div').appendChild(closeBtn);
        }
    }

    // Close sidebar event
    document.addEventListener('click', function(e) {
        if (e.target.id === 'closeAdvancedFilter' || 
            e.target.closest('#closeAdvancedFilter')) {
            console.log('Close button clicked');
            if (filterSidebar) {
                filterSidebar.style.right = '-380px';
                filterSidebar.classList.remove('show');
            }
        }
        
        // Close when clicking outside
        if (filterSidebar && filterSidebar.classList.contains('show') && 
            !filterSidebar.contains(e.target) && 
            e.target.id !== 'openAdvancedFilter') {
            console.log('Clicking outside sidebar');
            filterSidebar.style.right = '-380px';
            filterSidebar.classList.remove('show');
        }
    });

    // Apply advanced filter
    const applyAdvancedFilter = document.getElementById('applyAdvancedFilter');
    if (applyAdvancedFilter) {
        console.log('Apply filter button found');
        applyAdvancedFilter.addEventListener('click', function() {
            console.log('Apply filter clicked');
            
            const advField = document.getElementById('advField');
            const advCondition = document.getElementById('advCondition');
            const advValue = document.getElementById('advValue');
            
            if (advField && advCondition && advValue && filterForm) {
                // Remove existing hidden inputs
                const existing = filterForm.querySelectorAll('input[name^="adv_"]');
                existing.forEach(input => input.remove());
                
                // Add new hidden inputs
                const fieldInput = document.createElement('input');
                fieldInput.type = 'hidden';
                fieldInput.name = 'adv_field';
                fieldInput.value = advField.value;
                
                const condInput = document.createElement('input');
                condInput.type = 'hidden';
                condInput.name = 'adv_condition';
                condInput.value = advCondition.value;
                
                const valInput = document.createElement('input');
                valInput.type = 'hidden';
                valInput.name = 'adv_value';
                valInput.value = advValue.value.trim();
                
                filterForm.appendChild(fieldInput);
                filterForm.appendChild(condInput);
                filterForm.appendChild(valInput);
                
                // Close sidebar and submit
                if (filterSidebar) {
                    filterSidebar.style.right = '-380px';
                    filterSidebar.classList.remove('show');
                }
                
                filterForm.submit();
            }
        });
    }

    // Clear advanced filter
    const clearAdvancedFilter = document.getElementById('clearAdvancedFilter');
    if (clearAdvancedFilter) {
        clearAdvancedFilter.addEventListener('click', function() {
            console.log('Clear advanced filter clicked');
            
            // Remove advanced filter params from URL
            const url = new URL(window.location);
            url.searchParams.delete('adv_field');
            url.searchParams.delete('adv_condition');
            url.searchParams.delete('adv_value');
            
            // Close sidebar
            if (filterSidebar) {
                filterSidebar.style.right = '-380px';
                filterSidebar.classList.remove('show');
            }
            
            window.location.href = url.toString();
        });
    }

    // ================= DELETE FUNCTIONALITY =================
    // Single delete
    document.querySelectorAll('.openSingleDelete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const modalText = document.getElementById('deleteModalText');
            
            modalText.textContent = 'Are you sure you want to delete this organization?';
            
            const confirmBtn = document.getElementById('confirmDelete');
            const originalOnClick = confirmBtn.onclick;
            
            confirmBtn.onclick = function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = btn.dataset.action;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            };
            
            modal.show();
        });
    });

    // Bulk delete
    const openBulkDeleteModal = document.getElementById('openBulkDeleteModal');
    if (openBulkDeleteModal) {
        openBulkDeleteModal.addEventListener('click', function(e) {
            e.preventDefault();
            
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one organization');
                return;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const modalText = document.getElementById('deleteModalText');
            
            modalText.textContent = `Delete ${checked.length} selected organization(s)?`;
            
            const confirmBtn = document.getElementById('confirmDelete');
            confirmBtn.onclick = function() {
                document.getElementById('bulkDeleteForm').submit();
            };
            
            modal.show();
        });
    }

   
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                
                if (e.target.closest('input[type="checkbox"]')) {
                    return;
                }
                if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) {
                    return;
                }
                
                const url = this.dataset.url;
                if (url) {
                    console.log('Redirecting to:', url);
                    window.location.href = url;
                }
            });
        });

    // ================= ENTER KEY IN ADVANCED FILTER =================
    const advValue = document.getElementById('advValue');
    if (advValue && applyAdvancedFilter) {
        advValue.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyAdvancedFilter.click();
            }
        });
    }
  // organization.js में नीचे जोड़ें (existing code के बाद)

// ================= IMAGE MODAL FUNCTIONALITY =================
const imageModal = document.getElementById('imageModal');
if (!imageModal) {
    // Create image modal if not exists
    const modalHTML = `
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img id="modalImage" src="" class="img-fluid" style="max-height: 80vh;">
                </div>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Click event for organization images
document.addEventListener('click', function(e) {
    // Check if clicked on organization logo/image
    if (e.target.classList.contains('organization-logo') || 
        e.target.closest('.organization-logo')) {
        e.preventDefault();
        e.stopPropagation();
        
        const imgElement = e.target.tagName === 'IMG' ? e.target : e.target.querySelector('img');
        if (imgElement && imgElement.src) {
            const modalImage = document.getElementById('modalImage');
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            
            modalImage.src = imgElement.src;
            modalImage.alt = imgElement.alt || 'Organization Image';
            modal.show();
        }
    }
});  
});