<script>

function updateTotals()
{
    let subtotal = 0;
    let totalProducts = 0;
    let totalQuantity = 0;

    document.querySelectorAll('#purchaseItemsTable .purchase-row')
        .forEach(function(row){

            totalProducts++;

            const qty =
                parseFloat(
                    row.querySelector('.quantity').value
                ) || 0;

            const price =
                parseFloat(
                    row.querySelector('.purchase-price').value
                ) || 0;

            const rowTotal = qty * price;

            row.querySelector('.row-total').value =
                rowTotal.toFixed(2);

            subtotal += rowTotal;

            totalQuantity += qty;

        });

    const tax = 0;

    const grandTotal = subtotal + tax;

    document.getElementById('summaryProducts').textContent =
        totalProducts;

    document.getElementById('summaryQty').textContent =
        totalQuantity;

    document.getElementById('summarySubtotal').textContent =
        subtotal.toFixed(2);

    document.getElementById('summaryTax').textContent =
        tax.toFixed(2);

    document.getElementById('summaryGrandTotal').textContent =
        grandTotal.toFixed(2);

    if(document.getElementById('subtotalInput'))
        document.getElementById('subtotalInput').value = subtotal.toFixed(2);

    if(document.getElementById('taxInput'))
        document.getElementById('taxInput').value = tax.toFixed(2);

    if(document.getElementById('grandTotalInput'))
        document.getElementById('grandTotalInput').value = grandTotal.toFixed(2);
}

const purchaseDate =
    document.querySelector('input[name="purchase_date"]');

if (purchaseDate) {

    purchaseDate.addEventListener('change', function () {

        const date = new Date(this.value);

        if (!isNaN(date)) {

            document.getElementById('summaryPurchaseDate').innerText =
                date.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

        }

    });

}

document.addEventListener('DOMContentLoaded', function(){

    updateTotals();

    document.addEventListener('input', function(e){

        if(
            e.target.classList.contains('purchase-price') ||
            e.target.classList.contains('quantity')
        ){
            updateTotals();
        }

    });

    document.addEventListener('click', function(){

        setTimeout(function(){

            updateTotals();

        },100);

    });

});

</script>