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

        if (commissionText.includes('%') ||
            commissionText.toLowerCase().includes('percentage')) {

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
            commissionLabel.innerText =
                `Supplier Commission (${commissionData.label})`;
        }
        if (netEl) netEl.innerText = net.toFixed(2);
    };



    if (addBtn) {
        addBtn.addEventListener('click', () => {
            const html = template.innerHTML.replace(/__INDEX__/g, index++);
            tableBody.insertAdjacentHTML('beforeend', html);
            toggleEmptyRow();
            updateSummary();
        });
    }

  

    tableBody.addEventListener('click', (e) => {
        if (!e.target.classList.contains('remove-variant')) return;
        if (!confirm('Remove this variant?')) return;

        e.target.closest('tr').remove();
        toggleEmptyRow();
        setTimeout(updateSummary, 50);
    });

    
    tableBody.addEventListener('input', (e) => {

        if (e.target.classList.contains('qty') ||
            e.target.classList.contains('purchase')) {

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
</script>
