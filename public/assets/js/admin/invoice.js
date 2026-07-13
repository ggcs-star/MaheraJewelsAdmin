document.addEventListener('DOMContentLoaded', function() {
    // ===================== SELECT2 INIT =====================
    function initProductSearch(context) {
        context = context || document;
        context.querySelectorAll('.product-select').forEach(function(select) {
            if (!$(select).hasClass('select2-hidden-accessible')) {
                $(select).select2({
                    placeholder: 'Search product...',
                    width: '100%',
                    dropdownParent: $(select).closest('td')
                });
            }
        });
    }

    function initVariantSearch(context) {
        context = context || document;
        context.querySelectorAll('.variant-select').forEach(function(select) {
            if (!$(select).hasClass('select2-hidden-accessible')) {
                $(select).select2({
                    placeholder: 'Search variant...',
                    width: '100%',
                    dropdownParent: $(select).closest('td')
                });
            }
        });
    }

    // ===================== VARIABLES =====================
    var itemsBody = document.getElementById('invoiceItems');
    var addRowBtn = document.getElementById('addRow');
    var productTemplate = document.getElementById('productOptionsTemplate') ? document.getElementById('productOptionsTemplate').innerHTML : '';
    var rowIndex = document.querySelectorAll('#invoiceItems tr').length;
    var offlinePricing = window.offlinePricing || {};

    var customerSelect = document.getElementById('customerSelect');
    var customerMobile = document.getElementById('customerMobile');
    var customerAddress = document.getElementById('customerAddress');

    // ===================== CUSTOMER SELECT =====================
    if (customerSelect) {
        customerSelect.addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            customerMobile.value = selected.dataset.mobile || '';
            customerAddress.value = selected.dataset.address || '';
        });
        setTimeout(function() {
            var event = new Event('change');
            customerSelect.dispatchEvent(event);
        }, 100);
    }

    function addRow() {
        var row = document.createElement('tr');
        row.innerHTML =
            '<td><select name="items[' + rowIndex + '][product_id]" class="form-select product-select">' + productTemplate + '</select></td>' +
            '<td><select name="items[' + rowIndex + '][variant_id]" class="form-select variant-select"><option value="">Select Variant</option></select></td>' +
            '<td>' +
            '<input type="number" name="items[' + rowIndex + '][qty]" class="form-control qty" value="1" min="1">' +
            '<div class="small mt-1 text-muted stock-info" style="display:none;">Available: <strong class="available-stock">0</strong> | Remaining: <strong class="remaining-stock">0</strong></div>' +
            '<div id="stockError_' + rowIndex + '" class="text-danger small" style="display:none; margin-top:2px;">⚠️ Quantity exceeds available stock!</div>' +
            '</td>' +
            '<td><input type="number" name="items[' + rowIndex + '][price]" class="form-control price" readonly></td>' +
            '<td><div class="input-group"><input type="number" name="items[' + rowIndex + '][discount]" class="form-control discount" value="0" min="0"><select class="form-select discount-type" name="items[' + rowIndex + '][discount_type]"><option value="percent" selected>%</option><option value="flat">₹</option></select></div></td>' +
            '<td><input type="number" class="form-control total" readonly></td>' +
            '<td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow"><i class="fas fa-trash"></i></button></td>';
        itemsBody.appendChild(row);
        initProductSearch(row);
        initVariantSearch(row);
        
        // ✅ Naye row ke variant select pe change event
        var newVariantSelect = row.querySelector('.variant-select');
        if (newVariantSelect) {
            $(newVariantSelect).on('change', function() {
                var row = this.closest('tr');
                var selectedOption = this.options[this.selectedIndex];
                if (!selectedOption) return;

                var variantId = selectedOption.value;
                if (!variantId) return;

                var offlineQty = parseInt(selectedOption.dataset.offlineQuantity) || 0;
                var offlinePrice = parseFloat(selectedOption.dataset.offlinePrice) || 0;
                var offlineDiscount = parseFloat(selectedOption.dataset.offlineDiscount) || 0;
                var offlineDiscountType = selectedOption.dataset.offlineDiscountType || 'percentage';

                var qtyInput = row.querySelector('.qty');
                qtyInput.value = 1;
                qtyInput.max = offlineQty;

                // ✅ Stock info show karo
                var stockInfo = row.querySelector('.stock-info');
                if (stockInfo) {
                    stockInfo.style.display = 'block';
                    stockInfo.querySelector('.available-stock').innerText = offlineQty;
                    var remaining = offlineQty - 1;
                    if (remaining < 0) remaining = 0;
                    stockInfo.querySelector('.remaining-stock').innerText = remaining;
                }

                row.dataset.available = offlineQty;

                var rowIndex2 = Array.from(itemsBody.children).indexOf(row);
                var errorDiv = document.getElementById('stockError_' + rowIndex2);
                if (errorDiv) errorDiv.style.display = 'none';

                if (offlinePrice > 0) {
                    row.dataset.unitPrice = offlinePrice;
                    row.querySelector('.price').value = offlinePrice;
                } else {
                    var unitPrice = parseFloat(selectedOption.dataset.price) || 0;
                    row.dataset.unitPrice = unitPrice;
                    row.querySelector('.price').value = unitPrice;
                }

                if (offlineDiscount > 0) {
                    var discountInput = row.querySelector('.discount');
                    var discountType = row.querySelector('.discount-type');
                    if (discountInput) discountInput.value = offlineDiscount;
                    if (discountType) discountType.value = offlineDiscountType === 'percentage' ? 'percent' : 'flat';
                }

                calcRow(row);
            });
        }
        
        rowIndex++;
    }

    if (itemsBody && itemsBody.children.length === 0) {
        addRow();
    }

    if (addRowBtn) {
        addRowBtn.addEventListener('click', addRow);
    }

    document.querySelector('[name="paid_amount"]').addEventListener('input', calcSummary);

    // ===================== STOCK DISPLAY =====================
    function updateStockDisplay(row, variantId) {
        var offline = offlinePricing[variantId] || {};
        var available = parseInt(offline.quantity) || 0;
        var qtyInput = row.querySelector('.qty');
        var currentQty = parseInt(qtyInput.value) || 0;
        var remaining = available - currentQty;
        if (remaining < 0) remaining = 0;

        var stockInfo = row.querySelector('.stock-info');
        if (stockInfo) {
            stockInfo.style.display = 'block';
            stockInfo.querySelector('.available-stock').innerText = available;
            stockInfo.querySelector('.remaining-stock').innerText = remaining;
        }

        row.dataset.available = available;

        var rowIndex2 = Array.from(itemsBody.children).indexOf(row);
        var errorDiv = document.getElementById('stockError_' + rowIndex2);

        if (currentQty > available && currentQty > 0) {
            qtyInput.value = available;
            if (errorDiv) {
                errorDiv.style.display = 'block';
                errorDiv.innerText = '⚠️ Quantity exceeds available stock: ' + available;
            }
        } else {
            if (errorDiv) errorDiv.style.display = 'none';
        }
    }

    // ===================== CALCULATIONS =====================
    function calcRow(row) {
        var qty = parseInt(row.querySelector('.qty').value) || 0;
        var unitPrice = parseFloat(row.dataset.unitPrice) || 0;

        // ✅ Agar unitPrice 0 hai toh variant select se price lein
        if (unitPrice === 0) {
            var variantSelect = row.querySelector('.variant-select');
            var selectedOption = variantSelect.options[variantSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                unitPrice = parseFloat(selectedOption.dataset.offlinePrice) || parseFloat(selectedOption.dataset.price) || 0;
                row.dataset.unitPrice = unitPrice;
                
                // ✅ Stock info bhi set karo
                var offlineQty = parseInt(selectedOption.dataset.offlineQuantity) || 0;
                if (offlineQty > 0) {
                    var stockInfo = row.querySelector('.stock-info');
                    if (stockInfo) {
                        stockInfo.style.display = 'block';
                        stockInfo.querySelector('.available-stock').innerText = offlineQty;
                        var remaining = offlineQty - qty;
                        if (remaining < 0) remaining = 0;
                        stockInfo.querySelector('.remaining-stock').innerText = remaining;
                    }
                    row.dataset.available = offlineQty;
                    row.querySelector('.qty').max = offlineQty;
                }
            }
        }

        if (qty === 0 || unitPrice === 0) {
            row.querySelector('.price').value = '0.00';
            row.querySelector('.total').value = '0.00';
            calcSummary();
            return;
        }

        var priceTotal = qty * unitPrice;
        row.querySelector('.price').value = priceTotal.toFixed(2);

        var discountValue = parseFloat(row.querySelector('.discount').value) || 0;
        var discountType = row.querySelector('.discount-type').value || 'percent';
        var discountAmount = 0;

        if (discountType === 'percent') {
            discountAmount = (priceTotal * discountValue) / 100;
        } else {
            discountAmount = discountValue;
        }
        discountAmount = Math.min(discountAmount, priceTotal);
        var finalTotal = priceTotal - discountAmount;
        row.querySelector('.total').value = finalTotal.toFixed(2);
        calcSummary();
    }

    function calcSummary() {
        var totalQty = 0,
            totalPrice = 0,
            totalDiscount = 0,
            netTotal = 0;
        document.querySelectorAll('#invoiceItems tr').forEach(function(row) {
            var qty = parseInt(row.querySelector('.qty').value) || 0;
            var price = parseFloat(row.querySelector('.price').value) || 0;
            var lineTotal = parseFloat(row.querySelector('.total').value) || 0;
            totalQty += qty;
            totalPrice += price;
            netTotal += lineTotal;
            totalDiscount += Math.max(price - lineTotal, 0);
        });
        document.getElementById('totalQty').value = totalQty;
        document.getElementById('totalPrice').value = totalPrice.toFixed(2);
        document.getElementById('totalDiscountLabel').innerText = totalDiscount.toFixed(2);
        document.getElementById('grandTotal').innerText = netTotal.toFixed(2);
        document.getElementById('netTotal').innerText = netTotal.toFixed(2);
        var paid = parseFloat(document.querySelector('[name="paid_amount"]').value) || 0;
        document.getElementById('paidAmountDisplay').innerText = paid.toFixed(2);
        document.getElementById('dueAmount').innerText = Math.max(netTotal - paid, 0).toFixed(2);
        document.getElementById('changeAmount').innerText = Math.max(paid - netTotal, 0).toFixed(2);
    }

    // ===================== REMOVE ROW =====================
    itemsBody.addEventListener('click', function(e) {
        if (e.target.closest('.removeRow')) {
            if (itemsBody.children.length <= 1) {
                alert('At least one item is required');
                return;
            }
            e.target.closest('tr').remove();
            calcSummary();
        }
    });

    // ===================== QTY INPUT =====================
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('qty')) {
            var row = e.target.closest('tr');
            var variantSelect = row.querySelector('.variant-select');
            var variantId = variantSelect.value;
            var available = parseInt(row.dataset.available) || 0;
            var qty = parseInt(e.target.value) || 0;

            if (e.target.value === '') {
                var stockInfo = row.querySelector('.stock-info');
                if (stockInfo) {
                    stockInfo.querySelector('.available-stock').innerText = available;
                    stockInfo.querySelector('.remaining-stock').innerText = available;
                }
                var rowIndex2 = Array.from(itemsBody.children).indexOf(row);
                var errorDiv = document.getElementById('stockError_' + rowIndex2);
                if (errorDiv) errorDiv.style.display = 'none';
                calcRow(row);
                return;
            }

            if (variantId) {
                updateStockDisplay(row, variantId);
            }

            var rowIndex2 = Array.from(itemsBody.children).indexOf(row);
            var errorDiv = document.getElementById('stockError_' + rowIndex2);

            if (qty > available) {
                e.target.value = available;
                if (errorDiv) {
                    errorDiv.style.display = 'block';
                    errorDiv.innerText = '⚠️ Quantity exceeds available stock: ' + available;
                }
            } else {
                if (errorDiv) errorDiv.style.display = 'none';
            }

            calcRow(row);
        }

        if (e.target.classList.contains('discount') || e.target.name === 'paid_amount') {
            var row = e.target.closest('tr');
            if (row) {
                calcRow(row);
            } else {
                calcSummary();
            }
        }
    });

    // ===================== PRODUCT SELECT =====================
    $(document).on('select2:select', '.product-select', function(e) {
        var row = this.closest('tr');
        var productId = this.value;
        var variantSelect = row.querySelector('.variant-select');

        if ($(variantSelect).hasClass('select2-hidden-accessible')) {
            $(variantSelect).select2('destroy');
        }
        variantSelect.innerHTML = '';
        variantSelect.options.length = 0;
        var baseOption = document.createElement('option');
        baseOption.value = '';
        baseOption.textContent = 'Select Variant';
        variantSelect.appendChild(baseOption);

        row.dataset.unitPrice = 0;
        row.querySelector('.price').value = '';
        row.querySelector('.total').value = '';
        var stockInfo = row.querySelector('.stock-info');
        if (stockInfo) stockInfo.style.display = 'none';

        if (!productId) {
            initVariantSearch(row);
            return;
        }

        fetch('/admin/products/' + productId + '/variants')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                data.forEach(function(v) {
                    var option = document.createElement('option');
                    option.value = v.id;
                    option.dataset.price = v.selling_price || 0;
                    option.dataset.offlineQuantity = v.offline_quantity || 0;
                    option.dataset.offlinePrice = v.offline_price || 0;
                    option.dataset.offlineDiscount = v.offline_discount || 0;
                    option.dataset.offlineDiscountType = v.offline_discount_type || 'percentage';
                    option.textContent = v.sku_suffix;
                    variantSelect.appendChild(option);
                });
                initVariantSearch(row);
            });
    });

    // ===================== VARIANT SELECT =====================
    $(document).on('change', '.variant-select', function() {
        var row = this.closest('tr');
        var selectedOption = this.options[this.selectedIndex];
        if (!selectedOption) return;

        var variantId = selectedOption.value;
        if (!variantId) return;

        var offlineQty = parseInt(selectedOption.dataset.offlineQuantity) || 0;
        var offlinePrice = parseFloat(selectedOption.dataset.offlinePrice) || 0;
        var offlineDiscount = parseFloat(selectedOption.dataset.offlineDiscount) || 0;
        var offlineDiscountType = selectedOption.dataset.offlineDiscountType || 'percentage';

        var qtyInput = row.querySelector('.qty');
        qtyInput.value = 1;
        qtyInput.max = offlineQty;

        var stockInfo = row.querySelector('.stock-info');
        if (stockInfo) {
            stockInfo.style.display = 'block';
            stockInfo.querySelector('.available-stock').innerText = offlineQty;
            var remaining = offlineQty - 1;
            if (remaining < 0) remaining = 0;
            stockInfo.querySelector('.remaining-stock').innerText = remaining;
        }

        row.dataset.available = offlineQty;

        var rowIndex2 = Array.from(itemsBody.children).indexOf(row);
        var errorDiv = document.getElementById('stockError_' + rowIndex2);
        if (errorDiv) errorDiv.style.display = 'none';

        if (offlinePrice > 0) {
            row.dataset.unitPrice = offlinePrice;
            row.querySelector('.price').value = offlinePrice;
        } else {
            var unitPrice = parseFloat(selectedOption.dataset.price) || 0;
            row.dataset.unitPrice = unitPrice;
            row.querySelector('.price').value = unitPrice;
        }

        if (offlineDiscount > 0) {
            var discountInput = row.querySelector('.discount');
            var discountType = row.querySelector('.discount-type');
            if (discountInput) discountInput.value = offlineDiscount;
            if (discountType) discountType.value = offlineDiscountType === 'percentage' ? 'percent' : 'flat';
        }

        calcRow(row);
    });

    // ===================== SUBMIT VALIDATION =====================
    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        var rows = document.querySelectorAll('#invoiceItems tr');
        var hasValidRow = false;
        rows.forEach(function(row) {
            var productId = row.querySelector('.product-select').value;
            var variantId = row.querySelector('.variant-select').value;
            var qty = row.querySelector('.qty').value;
            if (productId && variantId && parseInt(qty) > 0) {
                hasValidRow = true;
            }
        });
        if (!hasValidRow) {
            e.preventDefault();
            alert('Please add at least one valid item (product, variant, and quantity)');
        }
    });

    // ===================== INIT =====================
    setTimeout(function() {
        initProductSearch();
        initVariantSearch();
        calcSummary();
    }, 100);

    // ===================== DISCOUNT TYPE SYNC =====================
    document.querySelectorAll('#invoiceItems tr').forEach(function(row) {
        var discountType = row.querySelector('.discount-type');
        if (discountType) {
            discountType.dispatchEvent(new Event('change'));
        }
    });

    // ===================== PRINT / DOWNLOAD =====================
    function triggerHiddenPrint(url) {
        var oldFrame = document.getElementById('print-frame');
        if (oldFrame) oldFrame.remove();
        var iframe = document.createElement('iframe');
        iframe.id = 'print-frame';
        iframe.style.position = 'fixed';
        iframe.style.top = '0';
        iframe.style.left = '0';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        iframe.style.visibility = 'hidden';
        iframe.onload = function() {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        };
        iframe.src = url;
        document.body.appendChild(iframe);
    }

    document.addEventListener('click', function(e) {
        var printBtn = e.target.closest('.js-print');
        if (printBtn) {
            e.preventDefault();
            e.stopPropagation();
            var id = printBtn.dataset.id;
            triggerHiddenPrint('/admin/invoices/' + id + '?print=1');
            return;
        }
        var downloadBtn = e.target.closest('.js-download');
        if (downloadBtn) {
            e.preventDefault();
            e.stopPropagation();
            var id = downloadBtn.dataset.id;
            window.location = '/admin/invoices/' + id + '?download=1';
            return;
        }
    });
});
// ===================== CUSTOMER MODAL =====================
var customerModalEl = document.getElementById('customerModal');
var customerModal = customerModalEl ? new bootstrap.Modal(customerModalEl) : null;

document.getElementById('openCustomerModal')?.addEventListener('click', function() {
    document.getElementById('newCustomerName').value = '';
    document.getElementById('newCustomerMobile').value = '';
    document.getElementById('newCustomerAddress').value = '';
    if (customerModal) customerModal.show();
});

document.getElementById('saveCustomer')?.addEventListener('click', function() {
    var name = document.getElementById('newCustomerName').value.trim();
    var mobile = document.getElementById('newCustomerMobile').value.trim();
    var address = document.getElementById('newCustomerAddress').value.trim();

    if (!name) {
        alert('Please enter customer name');
        return;
    }
    if (!/^\d{10}$/.test(mobile)) {
        alert('Please enter a valid 10 digit mobile number');
        document.getElementById('newCustomerMobile').focus();
        return;
    }

    fetch('/admin/customers/ajax-store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name: name, mobile: mobile, address_line_1: address })
    })
    .then(function(res) { return res.json(); })
    .then(function(customer) {
        var select = document.getElementById('customerSelect');
        var option = document.createElement('option');
        option.value = customer.id;
        option.dataset.mobile = customer.mobile || '';
        option.dataset.address = customer.address_line_1 || '';
        option.textContent = customer.name;
        option.selected = true;
        select.appendChild(option);
        document.getElementById('customerMobile').value = customer.mobile || '';
        document.getElementById('customerAddress').value = customer.address_line_1 || '';
        if (customerModal) customerModal.hide();
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Failed to save customer. Please try again.');
    });
});
// ===================== EDIT PAGE - EXISTING ROWS INIT =====================
document.querySelectorAll('#invoiceItems tr').forEach(function(row) {
    var variantSelect = row.querySelector('.variant-select');
    if (variantSelect && variantSelect.value) {
        var variantId = variantSelect.value;
        var selectedOption = variantSelect.options[variantSelect.selectedIndex];
        if (selectedOption) {
            var offlineQty = parseInt(selectedOption.dataset.offlineQuantity) || 0;
            var offlinePrice = parseFloat(selectedOption.dataset.offlinePrice) || 0;
            var offlineDiscount = parseFloat(selectedOption.dataset.offlineDiscount) || 0;
            var offlineDiscountType = selectedOption.dataset.offlineDiscountType || 'percentage';

            if (offlineQty === 0) {
                var offline = window.offlinePricing[variantId] || {};
                offlineQty = parseInt(offline.quantity) || 0;
                offlinePrice = parseFloat(offline.price) || 0;
                offlineDiscount = parseFloat(offline.discount_value) || 0;
                offlineDiscountType = offline.discount_type || 'percentage';
            }

            var stockInfo = row.querySelector('.stock-info');
            if (stockInfo) {
                stockInfo.style.display = 'block';
                stockInfo.querySelector('.available-stock').innerText = offlineQty;
                var currentQty = parseInt(row.querySelector('.qty').value) || 0;
                var remaining = offlineQty - currentQty;
                if (remaining < 0) remaining = 0;
                stockInfo.querySelector('.remaining-stock').innerText = remaining;
            }

            row.dataset.available = offlineQty;
            row.querySelector('.qty').max = offlineQty;

            if (offlinePrice > 0) {
                row.dataset.unitPrice = offlinePrice;
                row.querySelector('.price').value = offlinePrice;
            }

            if (offlineDiscount > 0) {
                var discountInput = row.querySelector('.discount');
                var discountType = row.querySelector('.discount-type');
                if (discountInput) discountInput.value = offlineDiscount;
                if (discountType) discountType.value = offlineDiscountType === 'percentage' ? 'percent' : 'flat';
            }

            calcRow(row);
        }
    }
});