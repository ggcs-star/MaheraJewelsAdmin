document.addEventListener('DOMContentLoaded', () => {
    function initProductSearch(context = document) {
    context.querySelectorAll('.product-select').forEach(select => {
        if (!select.classList.contains('select2-hidden-accessible')) {
            $(select).select2({
                placeholder: 'Search product...',
                width: '100%',
                dropdownParent: $(select).closest('td')
            });
        }
    });
}
function initVariantSearch(context = document) {
    context.querySelectorAll('.variant-select').forEach(select => {
        if (!select.classList.contains('select2-hidden-accessible')) {
            $(select).select2({
                placeholder: 'Search variant...',
                width: '100%',
                dropdownParent: $(select).closest('td')
            });
        }
    });
}


    const itemsBody   = document.getElementById('invoiceItems');
    const addRowBtn   = document.getElementById('addRow');
    

    const productTemplate =
        document.getElementById('productOptionsTemplate')?.innerHTML || '';

    let rowIndex = document.querySelectorAll('#invoiceItems tr').length;

    const customerSelect = document.getElementById('customerSelect');
    const customerMobile = document.getElementById('customerMobile');
    const customerAddress = document.getElementById('customerAddress');

    if (customerSelect) {
        customerSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const mobile = selected.dataset.mobile || '';
            const address = selected.dataset.address || '';
            
            customerMobile.value = mobile;
            customerAddress.value = address;
        });

        setTimeout(() => {
            const event = new Event('change');
            customerSelect.dispatchEvent(event);
        }, 100);
    }

    if (itemsBody && itemsBody.children.length === 0) {
        addRow();
    }

    addRowBtn?.addEventListener('click', addRow);
    document
    .querySelector('[name="paid_amount"]')
    ?.addEventListener('input', calcSummary);

    function addRow() {

        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]"
                        class="form-select product-select">
                    ${productTemplate}
                </select>
            </td>

            <td>
                <select name="items[${rowIndex}][variant_id]"
                        class="form-select variant-select">
                    <option value="">Select Variant</option>
                </select>
            </td>

            <td>
                <input type="number"
                       name="items[${rowIndex}][qty]"
                       class="form-control qty"
                       value="1"
                       min="1">
            </td>

            <td>
                <input type="number"
                       name="items[${rowIndex}][price]"
                       class="form-control price"
                       readonly>
            </td>
            <td>
    <div class="input-group">
        <input type="number"
               name="items[${rowIndex}][discount]"
               class="form-control discount"
               value="0"
               min="0">

       <select class="form-select discount-type"
        name="items[${rowIndex}][discount_type]">
    <option value="percent" selected>%</option>
    <option value="flat">₹</option>
</select>


    </div>
</td>

            <td>
                <input type="number"
                       class="form-control total"
                       readonly>
            </td>

            <td class="text-center">
                <button type="button"
                        class="btn btn-sm btn-danger removeRow">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        itemsBody.appendChild(row);
        initProductSearch(row);
        initVariantSearch(row);
        rowIndex++;
    }
setTimeout(() => {
    initProductSearch();
    initVariantSearch();
}, 0);

   
    itemsBody.addEventListener('click', e => {
        if (e.target.closest('.removeRow')) {
            if (itemsBody.children.length <= 1) {
                alert('At least one item is required');
                return;
            }
            e.target.closest('tr').remove();
            calcSummary();
        }
    });

function calcRow(row) {

    const qty = Number(row.querySelector('.qty')?.value || 0);
    const unitPrice = Number(row.dataset.unitPrice || row.querySelector('.price')?.value || 0);


    // 🔹 price column = qty × unit price
    const priceTotal = qty * unitPrice;
    row.querySelector('.price').value = priceTotal.toFixed(2);

    const discountValue =
        Number(row.querySelector('.discount')?.value || 0);

    const discountType =
        row.querySelector('.discount-type')?.value || 'percent';

    let discountAmount = 0;

    if (discountType === 'percent') {
        discountAmount = (priceTotal * discountValue) / 100;
    } else {
        discountAmount = discountValue; // ₹ flat
    }

    discountAmount = Math.min(discountAmount, priceTotal);

    const finalTotal = priceTotal - discountAmount;

    row.querySelector('.total').value = finalTotal.toFixed(2);

    calcSummary();
}

function calcSummary() {

    let totalQty = 0;
    let totalPrice = 0;     // without discount
    let totalDiscount = 0;  // discount amount
    let netTotal = 0;       // after discount

    document.querySelectorAll('#invoiceItems tr').forEach(row => {

        const qty = Number(row.querySelector('.qty')?.value || 0);
        const price = Number(row.querySelector('.price')?.value || 0);
        const lineTotal = Number(row.querySelector('.total')?.value || 0);

        totalQty += qty;
        totalPrice += price;
        netTotal += lineTotal;

        // discount = price - final total
        totalDiscount += Math.max(price - lineTotal, 0);
    });

    // 🔹 Bottom Total Amount row
    document.getElementById('totalQty').value = totalQty;
    document.getElementById('totalPrice').value = totalPrice.toFixed(2);

    // 🔹 Invoice Summary
    document.getElementById('totalDiscountLabel').innerText =
        totalDiscount.toFixed(2);

    document.getElementById('grandTotal').innerText =
        netTotal.toFixed(2);

    document.getElementById('netTotal').innerText =
        netTotal.toFixed(2);

    const paid = Number(
        document.querySelector('[name="paid_amount"]')?.value || 0
    );

    document.getElementById('paidAmountDisplay').innerText =
        paid.toFixed(2);

    document.getElementById('dueAmount').innerText =
        Math.max(netTotal - paid, 0).toFixed(2);

    document.getElementById('changeAmount').innerText =
        Math.max(paid - netTotal, 0).toFixed(2);
}


    const customerModalEl = document.getElementById('customerModal');
    const customerModal = customerModalEl ? new bootstrap.Modal(customerModalEl) : null;

    document.getElementById('openCustomerModal')?.addEventListener('click', () => {
        document.getElementById('newCustomerName').value = '';
        document.getElementById('newCustomerMobile').value = '';
        document.getElementById('newCustomerAddress').value = '';
        customerModal.show();
    });

    document.getElementById('saveCustomer')?.addEventListener('click', () => {

        const name    = document.getElementById('newCustomerName').value.trim();
        const mobile  = document.getElementById('newCustomerMobile').value.trim();
        const address = document.getElementById('newCustomerAddress').value.trim();

        if (!name) {
            alert('Please enter customer name');
            return;
        }
            // ✅ ONLY 10 digit mobile allowed
    if (!/^\d{10}$/.test(mobile)) {
        alert('Please enter a valid 10 digit mobile number');
        document.getElementById('newCustomerMobile').focus();
        return; // ⛔ stop save
    }


        fetch('/admin/customers/ajax-store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                name, 
                mobile, 
                address_line_1: address 
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('Failed to save customer');
            return res.json();
        })
        .then(customer => {

    const select = document.getElementById('customerSelect');
    const option = document.createElement('option');

    option.value = customer.id;
    option.dataset.mobile  = customer.mobile || '';
    option.dataset.address = customer.address_line_1 || '';
    option.textContent = customer.name;
    option.selected = true;

    select.appendChild(option);
     customerMobile.value  = customer.mobile || '';
    customerAddress.value = customer.address_line_1 || '';

    customerModal.hide();
    document.getElementById('customerMobile').value =
        customer.mobile || '';

    document.getElementById('customerAddress').value =
        customer.address_line_1 || '';

    customerModal.hide();
})

        .catch(error => {
            console.error('Error:', error);
            alert('Failed to save customer. Please try again.');
        });
    });

    document.getElementById('invoiceForm')?.addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('#invoiceItems tr');
        let hasValidRow = false;

        rows.forEach(row => {
            const productId = row.querySelector('.product-select')?.value;
            const variantId = row.querySelector('.variant-select')?.value;
            const qty = row.querySelector('.qty')?.value;

            if (productId && variantId && qty > 0) {
                hasValidRow = true;
            }
        });

        if (!hasValidRow) {
            e.preventDefault();
            alert('Please add at least one valid item (product, variant, and quantity)');
        }
    });
function triggerHiddenPrint(url) {

    const oldFrame = document.getElementById('print-frame');
    if (oldFrame) oldFrame.remove();

    const iframe = document.createElement('iframe');
    iframe.id = 'print-frame';

    // 👇 invisible iframe (NO tab, NO redirect)
    iframe.style.position = 'fixed';
    iframe.style.top = '0';
    iframe.style.left = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = '0';
    iframe.style.visibility = 'hidden';

    iframe.onload = function () {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    };

    iframe.src = url;
    document.body.appendChild(iframe);
}


setTimeout(() => {
    calcSummary();
}, 100);

// qty / discount / price change pe live update
document.addEventListener('input', function (e) {

    if (
        e.target.classList.contains('qty') ||
        e.target.classList.contains('discount') ||
        e.target.name === 'paid_amount'
    ) {
        const row = e.target.closest('tr');
        if (row) {
            calcRow(row);
        } else {
            calcSummary();
        }
    }
});
// ================= INDEX PAGE PRINT / DOWNLOAD =================

document.addEventListener('click', function (e) {

    // 🖨 PRINT
    const printBtn = e.target.closest('.js-print');
    if (printBtn) {
        e.preventDefault();
        e.stopPropagation();

        const id = printBtn.dataset.id;
        triggerHiddenPrint(`/admin/invoices/${id}?print=1`);
        return;
    }
const downloadBtn = e.target.closest('.js-download');
if (downloadBtn) {
    e.preventDefault();
    e.stopPropagation();

    const id = downloadBtn.dataset.id;
    window.location = `/admin/invoices/${id}?download=1`;
    return;
}


});
$(document).on('select2:select', '.product-select', function (e) {

    const row = this.closest('tr');
    const productId = this.value;
    const variantSelect = row.querySelector('.variant-select');

    // 🔥 1️⃣ DESTROY select2 properly
    if ($(variantSelect).hasClass('select2-hidden-accessible')) {
        $(variantSelect).select2('destroy');
    }

    // 🔥 2️⃣ HARD RESET (THIS IS IMPORTANT)
    variantSelect.innerHTML = '';
    variantSelect.options.length = 0;

    // base option
    const baseOption = document.createElement('option');
    baseOption.value = '';
    baseOption.textContent = 'Select Variant';
    variantSelect.appendChild(baseOption);

    row.dataset.unitPrice = 0;
    row.querySelector('.price').value = '';
    row.querySelector('.total').value = '';

    if (!productId) {
        initVariantSearch(row);
        return;
    }

    fetch(`/admin/products/${productId}/variants`)
        .then(res => res.json())
        .then(data => {

            data.forEach(v => {
                const option = document.createElement('option');
                option.value = v.id;
                option.dataset.price = v.selling_price || 0;
                option.textContent = v.sku_suffix;
                variantSelect.appendChild(option);
            });

            // 🔥 3️⃣ re-init select2 ONLY ONCE
            initVariantSearch(row);
        });
});

$(document).on('change', '.variant-select', function () {

    const row = this.closest('tr');

    // 🔥 ALWAYS reliable
    const selectedOption = this.options[this.selectedIndex];

    if (!selectedOption) return;

    const unitPrice = Number(selectedOption.dataset.price || 0);

    row.dataset.unitPrice = unitPrice;

    calcRow(row);
});
// ✅ EDIT PAGE FIX — discount type sync
document.querySelectorAll('#invoiceItems tr').forEach(row => {
    const discountType = row.querySelector('.discount-type');
    if (discountType) {
        discountType.dispatchEvent(new Event('change'));
    }
});

setTimeout(() => {
    calcSummary();
}, 100);


});

