<script>

document.addEventListener('DOMContentLoaded', function () {

    const supplierSelect = document.getElementById('supplierSelect');

    const supplierDetails = document.getElementById('supplierDetails');

    if (!supplierSelect) {
        return;
    }

    if (supplierSelect.value) {
        supplierDetails.classList.remove('hidden');
    }

    supplierSelect.addEventListener('change', function () {

        const supplierId = this.value;

        if (!supplierId) {

            supplierDetails.classList.add('hidden');

            return;

        }

        fetch(
            "/admin/purchase-orders/supplier/" + supplierId
        )

        .then(response => response.json())

        .then(function (supplier) {

            supplierDetails.classList.remove('hidden');

            document.getElementById('supplierName').innerHTML =
                supplier.name ?? '-';

            document.getElementById('supplierCompany').innerHTML =
                supplier.company_name ?? '-';

            document.getElementById('supplierPhone').innerHTML =
                supplier.phone ?? '-';

            document.getElementById('supplierEmail').innerHTML =
                supplier.email ?? '-';

            document.getElementById('supplierGST').innerHTML =
                supplier.gst_number ?? '-';

            document.getElementById('supplierPayment').innerHTML =
                supplier.payment_terms ?? '-';

            document.getElementById('supplierAddress').innerHTML =
                supplier.address ?? '-';

            document.getElementById('supplierLocation').innerHTML =

                (supplier.city ?? '') +

                ' ' +

                (supplier.state ?? '') +

                ' ' +

                (supplier.country ?? '') +

                ' ' +

                (supplier.pincode ?? '');

            const summarySupplier = document.getElementById('summarySupplier');

            if (summarySupplier) {

                summarySupplier.innerHTML = supplier.name;

            }

        });

    });

});
</script>