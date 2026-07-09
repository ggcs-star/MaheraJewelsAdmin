<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

        <div class="flex items-center gap-2">

            <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">

                <svg class="w-4 h-4 text-[#8B2452]"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>

                </svg>

            </div>

            <h3 class="text-base font-bold text-gray-800">

                Supplier Invoice

            </h3>

            <span class="ml-auto text-xs text-gray-400">

                Upload supplier bill

            </span>

        </div>

    </div>

    <div class="p-5">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div>

                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">

                    Upload Invoice

                </label>

                <input
                    type="file"
                    name="invoice_file"
                    id="invoiceFile"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2">

                <p class="text-xs text-gray-400 mt-2">

                    Supported: PDF, JPG, JPEG, PNG (Max 4MB)

                </p>

                @if(isset($purchaseOrder) && $purchaseOrder->invoice_file)
                    <p class="text-xs text-[#8B2452] mt-1">
                        Current file: {{ basename($purchaseOrder->invoice_file) }}
                    </p>
                @endif

            </div>

            <div>

                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">

                    Preview

                </label>

                <div
                    id="invoicePreview"
                    class="border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 h-56 flex items-center justify-center">

                    @if(isset($purchaseOrder) && $purchaseOrder->invoice_file)
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                            </svg>
                            <a href="{{ Storage::url($purchaseOrder->invoice_file) }}" target="_blank" class="text-[#8B2452] font-semibold hover:underline">
                                View Uploaded Invoice
                            </a>
                            <p class="text-xs text-gray-400 mt-1">{{ basename($purchaseOrder->invoice_file) }}</p>
                        </div>
                    @else
                        <div id="invoicePlaceholder" class="text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 8v4m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            <p class="text-sm text-gray-400">Invoice preview will appear here</p>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('invoiceFile');
    const preview = document.getElementById('invoicePreview');
    const placeholder = document.getElementById('invoicePlaceholder');

    if (fileInput) {
        fileInput.addEventListener('change', function () {

            preview.innerHTML = '';

            const file = this.files[0];

            if (!file) {
                if (placeholder) {
                    preview.appendChild(placeholder);
                }
                return;
            }

            const fileURL = URL.createObjectURL(file);

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = fileURL;
                img.className = 'w-full h-full object-contain rounded-lg';
                preview.appendChild(img);
            }
            else if (file.type === 'application/pdf') {
                const embed = document.createElement('embed');
                embed.src = fileURL;
                embed.type = 'application/pdf';
                embed.className = 'w-full h-full rounded-lg';
                preview.appendChild(embed);
            }
            else {
                preview.innerHTML =
                    '<div class="text-center">' +
                    '<svg class="w-12 h-12 mx-auto text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v9a2 2 0 01-2 2z"/>' +
                    '</svg>' +
                    '<p class="text-sm font-semibold">' +
                    file.name +
                    '</p>' +
                    '</div>';
            }

        });
    }

});
</script>
@endpush