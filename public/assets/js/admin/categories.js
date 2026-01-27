document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.category-row').forEach(row => {
        row.addEventListener('click', () => {
            const id = row.dataset.id;
            const icon = row.querySelector('.toggle-icon');

            document
                .querySelectorAll('.parent-' + id)
                .forEach(child => child.classList.toggle('d-none'));

            if (icon) {
                icon.textContent = icon.textContent === '▶' ? '▼' : '▶';
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('categorySearch');
    const clearBtn = document.getElementById('clearSearch');
    const form = document.getElementById('filterForm');

    if (searchInput && clearBtn && form) {
        let debounceTimer;

        const toggleClearIcon = () => {
            clearBtn.style.display = searchInput.value ? 'block' : 'none';
        };

        searchInput.addEventListener('input', () => {
            toggleClearIcon();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => form.submit(), 400);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            toggleClearIcon();
            form.submit();
        });

        toggleClearIcon();
    }
    if (form) {
    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => {
            form.submit();
        });
    });
}

    const modal = document.getElementById('imagePreviewModal');
    const previewImg = document.getElementById('previewImage');

    if (modal && previewImg) {
        document.querySelectorAll('.category-image').forEach(img => {
            img.addEventListener('click', (e) => {
                e.stopPropagation();
                previewImg.src = img.dataset.full;
                modal.classList.add('active');
            });
        });

        modal.addEventListener('click', () => {
            modal.classList.remove('active');
            previewImg.src = '';
        });
    }

});
document.addEventListener('DOMContentLoaded', () => {
    const openBtn = document.getElementById('openFilterSidebar');
    const closeBtn = document.getElementById('closeFilterSidebar');
    const sidebar = document.getElementById('filterSidebar');

    if (openBtn && sidebar) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });
    }

    if (closeBtn && sidebar) {
        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    }
});
document.addEventListener('DOMContentLoaded', () => {
    const applyBtn = document.getElementById('applyAdvancedFilter');
    const form = document.getElementById('filterForm');

    if (applyBtn && form) {
        applyBtn.addEventListener('click', () => {
            const field = document.getElementById('advField')?.value;
            const condition = document.getElementById('advCondition')?.value;
            const value = document.getElementById('advValue')?.value;

            if (!field || !value) return;

            let fieldInput = form.querySelector('input[name="adv_field"]');
            let conditionInput = form.querySelector('input[name="adv_condition"]');
            let valueInput = form.querySelector('input[name="adv_value"]');

            if (!fieldInput) {
                fieldInput = document.createElement('input');
                fieldInput.type = 'hidden';
                fieldInput.name = 'adv_field';
                form.appendChild(fieldInput);
            }

            if (!conditionInput) {
                conditionInput = document.createElement('input');
                conditionInput.type = 'hidden';
                conditionInput.name = 'adv_condition';
                form.appendChild(conditionInput);
            }

            if (!valueInput) {
                valueInput = document.createElement('input');
                valueInput.type = 'hidden';
                valueInput.name = 'adv_value';
                form.appendChild(valueInput);
            }

            fieldInput.value = field;
            conditionInput.value = condition;
            valueInput.value = value;

            form.submit();
        });
    }
});
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-icon').forEach(icon => {
        icon.addEventListener('click', e => {
            e.stopPropagation();

            const row = icon.closest('.category-row');
            const id = row.dataset.id;

            document
                .querySelectorAll('.parent-' + id)
                .forEach(child => child.classList.toggle('d-none'));

            icon.textContent = icon.textContent === '▶' ? '▼' : '▶';
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.addEventListener('click', e => {
            e.stopPropagation();
        });

        cb.addEventListener('mousedown', e => {
            e.stopPropagation();
        });
    });
});

// आपके JavaScript फाइल में
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const form = document.getElementById('bulkDeleteForm');

    // 1️⃣ Select All toggle
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    }

    // 2️⃣ Prevent submit if nothing selected
    if (form) {
        form.addEventListener('submit', function (e) {
            const checked = document.querySelectorAll('.row-checkbox:checked');

            if (checked.length === 0) {
                e.preventDefault();
                alert('Please select at least one category to delete.');
                return false;
            }
            
            // _method field को हटाएं अगर है तो
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) {
                methodField.remove();
            }
            
            return confirm(`Are you sure you want to delete ${checked.length} category(ies)?`);
        });
    }
});

