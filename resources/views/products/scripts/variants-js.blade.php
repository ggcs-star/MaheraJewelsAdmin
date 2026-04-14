<script>
document.addEventListener('DOMContentLoaded', () => {

    const addBtn        = document.getElementById('addVariantBtn');
    const tableBody    = document.querySelector('#variantTable tbody');
    const template     = document.getElementById('variantRowTemplate');
    const emptyRow     = document.getElementById('variantEmptyRow');

    const supplierSelect  = document.getElementById('supplierSelect');
    const grossEl         = document.getElementById('grossTotal');
    const commissionEl    = document.getElementById('commissionAmount');
    const commissionLabel = document.getElementById('commissionLabel');
    const netEl           = document.getElementById('netPayable');

    if (!tableBody || !template) return;

    let index = tableBody.querySelectorAll('tr').length;

    const toggleEmptyRow = () => {
        if (!emptyRow) return;
        const rows = tableBody.querySelectorAll('tr:not(#variantEmptyRow)');
        emptyRow.style.display = rows.length ? 'none' : '';
    };

    const calculateRowTotal = (row) => {
        const qty      = parseFloat(row.querySelector('.qty')?.value) || 0;
        const purchase = parseFloat(row.querySelector('.purchase')?.value) || 0;
        const totalEl  = row.querySelector('.total');
        if (totalEl) {
            totalEl.value = (qty * purchase).toFixed(2);
        }
    };

    const calculateGrossTotal = () => {
        let total = 0;
        tableBody.querySelectorAll('tr').forEach(row => {
            const totalInput = row.querySelector('.total');
            if (totalInput) {
                total += parseFloat(totalInput.value) || 0;
            }
        });
        return total;
    };

    const calculateCommission = (grossTotal) => {
        if (!supplierSelect || !supplierSelect.value) {
            commissionLabel.innerText = 'Supplier Commission';
            return { amount: 0, label: '' };
        }
        const opt = supplierSelect.options[supplierSelect.selectedIndex];
        const supplierName   = opt.dataset.name || opt.text;
        const commissionText = opt.dataset.commission || '';
        let amount = 0;
        let label  = '';
        if (commissionText.includes('%') || commissionText.toLowerCase().includes('percentage')) {
            const percent = parseFloat(commissionText.match(/\d+(\.\d+)?/));
            amount = (grossTotal * percent) / 100;
            label  = `${supplierName} – ${percent}%`;
        }
        else {
            const fixed = parseFloat(commissionText.match(/\d+(\.\d+)?/));
            amount = fixed || 0;
            label  = `${supplierName} – ₹${fixed} fixed`;
        }
        return { amount, label };
    };

    const updateSummary = () => {
        const gross = calculateGrossTotal();
        const commissionData = calculateCommission(gross);
        const net = gross + commissionData.amount;
        if (grossEl) grossEl.innerText = gross.toFixed(2);
        if (commissionEl) commissionEl.innerText = commissionData.amount.toFixed(2);
        if (commissionLabel) {
            commissionLabel.innerText = `Supplier Commission (${commissionData.label})`;
        }
        if (netEl) netEl.innerText = net.toFixed(2);
    };

    if (addBtn) {
    addBtn.addEventListener('click', () => {
        const html = template.innerHTML.replace(/__INDEX__/g, index++);
        tableBody.insertAdjacentHTML('beforeend', html);
        toggleEmptyRow();
        updateSummary();
        updateColorCells();
        
        const rows = tableBody.querySelectorAll('tr.variant-row');
        rows.forEach((row, idx) => {
            const hiddenField = row.querySelector('.selected-gallery-image');
            const fileInput = row.querySelector('.variant-image-file');
            const chooseBtn = row.querySelector('.choose-file-btn');
            const autoText = row.querySelector('.auto-text');
            
            if(idx === 0) {
                if(hiddenField) hiddenField.style.display = '';
                if(fileInput) fileInput.style.display = 'none';
                if(chooseBtn) chooseBtn.style.display = 'none';
                if(autoText) autoText.style.display = 'inline';
            } else {
                if(hiddenField) hiddenField.style.display = 'none';
                if(fileInput) fileInput.style.display = 'none';
                if(chooseBtn) chooseBtn.style.display = 'inline-block';
                if(autoText) autoText.style.display = 'none';
            }
        });
        
        setupFileUpload();
        syncSelectedImageToVariants();
    });
}
    tableBody.addEventListener('click', (e) => {
        if (!e.target.classList.contains('remove-variant')) return;
        if (!confirm('Remove this variant?')) return;
        e.target.closest('tr').remove();
        toggleEmptyRow();
        setTimeout(updateSummary, 50);
        updateColorCells();
    });

    tableBody.addEventListener('input', (e) => {
        if (e.target.classList.contains('qty') || e.target.classList.contains('purchase')) {
            calculateRowTotal(e.target.closest('tr'));
            updateSummary();
        }
    });

    if (supplierSelect) {
        supplierSelect.addEventListener('change', updateSummary);
    }

    setTimeout(() => {
        tableBody.querySelectorAll('tr').forEach(row => {
            calculateRowTotal(row);
        });
        toggleEmptyRow();
        updateSummary();
    }, 0);

});

function updateColorCells() {
    document.querySelectorAll('.variant-row').forEach(row => {
        const typeSelect = row.querySelector('.variant-type');
        const blocker = row.querySelector('.color-blocker');
        const input   = row.querySelector('.variant-color');
        if (!typeSelect || !blocker || !input) return;
        const currentColor = input.value;
        const typeName = typeSelect.options[typeSelect.selectedIndex]?.text.toLowerCase() || '';
        const allowColorTypes = ['size', 'color', 'storage'];
        const allowColor = allowColorTypes.some(t => typeName.includes(t));
        if (allowColor) {
            blocker.style.display = 'none';
            input.classList.remove('bg-light');
        } else {
            blocker.style.display = 'block';
            input.classList.add('bg-light');
        }
        input.value = currentColor;
    });
}

function syncSelectedImageToVariants() {
    if (!window.selectedGalleryImage) return;
    document.querySelectorAll('.selected-gallery-image').forEach(input => {
        input.value = window.selectedGalleryImage;
    });
}

function autoCreateFirstVariant() {
    const addBtn = document.getElementById('addVariantBtn');
    if (!addBtn) return;
    const rows = document.querySelectorAll('#variantTable tbody tr');
    if (rows.length === 0 || (rows.length === 1 && rows[0].id === 'variantEmptyRow')) {
        addBtn.click();
        setTimeout(() => {
            syncSelectedImageToVariants();
            autoFillFirstVariant();
        }, 300);
    }
}

function autoFillFirstVariant() {
    const firstRow = document.querySelector('#variantTable tbody tr:not(#variantEmptyRow)');
    if (!firstRow) return;
    const imageInput = firstRow.querySelector('.selected-gallery-image');
    if (imageInput && window.selectedGalleryImage) {
        imageInput.value = window.selectedGalleryImage;
    }
    const productPrice = document.querySelector('input[name="product_price"]');
    const sellingField = firstRow.querySelector('.selling');
    if (productPrice && sellingField && productPrice.value) {
        sellingField.value = productPrice.value;
    }
    const productSku = document.querySelector('input[name="sku"]');
    const skuField = firstRow.querySelector('input[name*="sku_suffix"]');
    if (productSku && skuField && productSku.value) {
        skuField.value = productSku.value;
    }
}
function setupFileUpload() {
    document.querySelectorAll('.choose-file-btn').forEach((btn) => {
        btn.onclick = function() {
            const row = this.closest('tr');
            const fileInput = row.querySelector('.variant-image-file');
            fileInput.click();
        }
    });
}
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        autoCreateFirstVariant();
         setupFileUpload();
    }, 400);
});

document.addEventListener('DOMContentLoaded', updateColorCells);

document.addEventListener('change', function (e) {
    if (!e.target.classList.contains('variant-type')) return;
    const row = e.target.closest('tr');
    const option = e.target.options[e.target.selectedIndex];
    const variantId = e.target.value;
    const hasDimensions = option.dataset.hasDimensions === '1';
    const heightInput = row.querySelector('input[name*="[height]"]');
    const widthInput  = row.querySelector('input[name*="[width]"]');
    if (heightInput && widthInput) {
        if (hasDimensions) {
            heightInput.disabled = false;
            widthInput.disabled  = false;
            heightInput.classList.remove('bg-light');
            widthInput.classList.remove('bg-light');
        } else {
            heightInput.value = '';
            widthInput.value  = '';
            heightInput.disabled = true;
            widthInput.disabled  = true;
            heightInput.classList.add('bg-light');
            widthInput.classList.add('bg-light');
        }
    }
    const valueSelect = row.querySelector('.variant-value');
    const selectedId  = valueSelect?.dataset.selectedId;
    if (!valueSelect) return;
    valueSelect.innerHTML = `<option value="">Loading...</option>`;
    if (!variantId) {
        valueSelect.innerHTML = `<option value="">Select value</option>`;
        return;
    }
    fetch(`/admin/variants/${variantId}/values`)
        .then(res => res.json())
        .then(data => {
            let options = `<option value="">Select value</option>`;
            data.forEach(v => {
                const isSelected = selectedId && String(selectedId) === String(v.id);
                options += `<option value="${v.id}" ${isSelected ? 'selected' : ''}>${v.value}</option>`;
            });
            valueSelect.innerHTML = options;
            if (selectedId) {
                valueSelect.value = selectedId;
            }
            if (!selectedId && data.length === 1) {
                valueSelect.value = data[0].id;
            }
            updateColorCells();
        });
});
</script>