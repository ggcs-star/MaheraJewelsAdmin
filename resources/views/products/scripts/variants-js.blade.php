<script>
document.addEventListener('DOMContentLoaded', () => {

    const addBtn      = document.getElementById('addVariantBtn');
    const tableBody  = document.querySelector('#variantTable tbody');
    const template   = document.getElementById('variantRowTemplate');
    const emptyRow   = document.getElementById('variantEmptyRow');

    if (!addBtn || !tableBody || !template) return;

    let index = 0;

    /* ---------------- HELPERS ---------------- */

    const toggleEmptyRow = () => {
        const rows = tableBody.querySelectorAll('tr:not(#variantEmptyRow)');
        emptyRow.style.display = rows.length ? 'none' : '';
    };

    const calculateRowTotal = (row) => {
        const qty      = parseFloat(row.querySelector('.qty')?.value) || 0;
        const purchase = parseFloat(row.querySelector('.purchase')?.value) || 0;
        const totalEl  = row.querySelector('.total');

        totalEl.value = (qty * purchase).toFixed(2);
    };

    /* ---------------- ADD VARIANT ---------------- */

    addBtn.addEventListener('click', () => {
        const html = template.innerHTML.replace(/__INDEX__/g, index++);
        tableBody.insertAdjacentHTML('beforeend', html);
        toggleEmptyRow();
    });

    /* ---------------- REMOVE VARIANT ---------------- */

    tableBody.addEventListener('click', (e) => {
        if (!e.target.classList.contains('remove-variant')) return;

        if (!confirm('Remove this variant?')) return;

        e.target.closest('tr').remove();
        toggleEmptyRow();
    });

    /* ---------------- AUTO TOTAL CALC ---------------- */

    tableBody.addEventListener('input', (e) => {
        if (!e.target.classList.contains('qty') &&
            !e.target.classList.contains('purchase')) return;

        calculateRowTotal(e.target.closest('tr'));
    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const supplierSelect = document.getElementById('supplierSelect');

    const grossEl = document.getElementById('grossTotal');
    const commissionEl = document.getElementById('commissionAmount');
    const commissionLabel = document.getElementById('commissionLabel');
    const netEl = document.getElementById('netPayable');

    const tableBody = document.querySelector('#variantTable tbody');

    /* ---------------- CALCULATE GROSS TOTAL ---------------- */

    function calculateGrossTotal() {
        let total = 0;

        tableBody.querySelectorAll('tr').forEach(row => {
            const totalInput = row.querySelector('.total');
            if (totalInput) {
                total += parseFloat(totalInput.value) || 0;
            }
        });

        return total;
    }

    /* ---------------- CALCULATE COMMISSION ---------------- */

    function calculateCommission(grossTotal) {

        if (!supplierSelect.value) {
            commissionLabel.innerText = 'Supplier Commission';
            return { amount: 0, label: '' };
        }

        const opt = supplierSelect.options[supplierSelect.selectedIndex];
        const supplierName = opt.dataset.name || opt.text;
        const commissionText = opt.dataset.commission || '';

        let commissionAmount = 0;
        let commissionLabelText = '';

        // percentage
        if (commissionText.includes('%') || commissionText.toLowerCase().includes('percentage')) {
            const percent = parseFloat(commissionText.match(/\d+(\.\d+)?/));
            commissionAmount = (grossTotal * percent) / 100;
            commissionLabelText = `${supplierName} – ${percent}%`;
        }
        // fixed
        else {
            const fixed = parseFloat(commissionText.match(/\d+(\.\d+)?/));
            commissionAmount = fixed || 0;
            commissionLabelText = `${supplierName} – ₹${fixed} fixed`;
        }

        return {
            amount: commissionAmount,
            label: commissionLabelText
        };
    }

    /* ---------------- UPDATE SUMMARY ---------------- */

function updateSummary() {
    const gross = calculateGrossTotal();
    const commissionData = calculateCommission(gross);

    // Net cost = total price + commission
    const net = gross + commissionData.amount;

    grossEl.innerText = gross.toFixed(2);
    commissionEl.innerText = commissionData.amount.toFixed(2);
    commissionLabel.innerText = `Supplier Commission (${commissionData.label})`;
    netEl.innerText = net.toFixed(2);
}


    /* ---------------- EVENTS ---------------- */

    tableBody.addEventListener('input', e => {
        if (e.target.classList.contains('qty') ||
            e.target.classList.contains('purchase')) {
            updateSummary();
        }
    });

    tableBody.addEventListener('click', e => {
        if (e.target.classList.contains('remove-variant')) {
            setTimeout(updateSummary, 50);
        }
    });

    supplierSelect.addEventListener('change', updateSummary);

});
</script>
