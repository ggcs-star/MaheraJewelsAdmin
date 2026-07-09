<script>
document.addEventListener('DOMContentLoaded', function () {

    const tableBody = document.getElementById('purchaseItemsTable');

    const addRowButton = document.getElementById('addProductRow');

    const rowTemplate = document.getElementById('purchaseRowTemplate');

    function calculateRow(row)
    {
        const qty =
            parseFloat(
                row.querySelector('.quantity').value
            ) || 0;

        const price =
            parseFloat(
                row.querySelector('.purchase-price').value
            ) || 0;

        const total = qty * price;

        row.querySelector('.row-total').value =
            total.toFixed(2);

        updateTotals();
    }

    function bindRow(row)
    {
        const product =
            row.querySelector('.product-select');

        const variant =
            row.querySelector('.variant-select');

        const quantity =
            row.querySelector('.quantity');

        const purchasePrice =
            row.querySelector('.purchase-price');

        product.addEventListener('change', function () {

            variant.innerHTML =
                '<option>Loading...</option>';

            fetch(
                "/admin/purchase-orders/product/" +
                this.value +
                "/variants"
            )

            .then(response => response.json())

            .then(function (variants) {

                variant.innerHTML =
                    '<option value="">Select Variant</option>';

                variants.forEach(function(item){

                let option = document.createElement('option');

                option.value = item.id;

                option.dataset.purchase = item.purchase_price;

                option.innerHTML =
                    item.variant_name +
                    " : " +
                    item.variant_value;

                variant.appendChild(option);

            });
            variant.addEventListener('change', function () {

    calculateRow(row);

    updateTotals();

});
            });

        });

        quantity.addEventListener('input', function(){

            calculateRow(row);

        });

        purchasePrice.addEventListener('input', function(){

            calculateRow(row);

        });

        row.querySelector('.removeRow')

        .addEventListener('click', function(){

            if(
                document.querySelectorAll('.purchase-row').length == 1
            ){
                return;
            }

            row.remove();

            updateTotals();

        });

    }

    addRowButton.addEventListener('click', function(){

        let clone =
            rowTemplate.content.cloneNode(true);

        tableBody.appendChild(clone);

        let newRow =
    tableBody.lastElementChild;

bindRow(newRow);

updateTotals();

    });

    document
.querySelectorAll('.purchase-row')
.forEach(function(row){

    bindRow(row);

});

updateTotals();

});

</script>