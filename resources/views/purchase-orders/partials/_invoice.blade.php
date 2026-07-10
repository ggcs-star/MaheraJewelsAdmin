<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-rose-50/80 to-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#8B2452]/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-800">Supplier Invoice</h3>
                <p class="text-xs text-gray-400">Upload supplier bill</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Upload Invoice
                </label>
                <div class="relative">
                    <input type="file" name="invoice_file" id="invoiceFile"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#8B2452] file:text-white hover:file:bg-[#6B1A3E] transition-colors duration-200">
                </div>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Supported: PDF, JPG, JPEG, PNG (Max 4MB)
                </p>
                @if(isset($purchaseOrder) && $purchaseOrder->invoice_file)
                    <p class="text-xs text-[#8B2452] mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Current: {{ basename($purchaseOrder->invoice_file) }}
                    </p>
                @endif
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Preview
                </label>
                <div id="invoicePreview"
                    class="border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/60 h-56 flex items-center justify-center transition-all duration-300 hover:border-[#8B2452]/30">

                    @if(isset($purchaseOrder) && $purchaseOrder->invoice_file)
                        <div class="text-center p-4">
                            <svg class="w-14 h-14 mx-auto text-[#8B2452] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                            </svg>
                            <a href="{{ Storage::disk('s3')->url($purchaseOrder->invoice_file) }}" target="_blank"
                                class="text-[#8B2452] font-semibold hover:underline inline-flex items-center gap-2">
                                View Uploaded Invoice
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            <p class="text-xs text-gray-400 mt-2">{{ basename($purchaseOrder->invoice_file) }}</p>
                        </div>
                    @else
                        <div id="invoicePlaceholder" class="text-center p-4">
                            <svg class="w-14 h-14 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 8v4m0 0l4-4m-4 4l-4-4"/>
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
                if (placeholder) preview.appendChild(placeholder);
                return;
            }
            const fileURL = URL.createObjectURL(file);
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = fileURL;
                img.className = 'w-full h-full object-contain rounded-xl p-4';
                preview.appendChild(img);
            } else if (file.type === 'application/pdf') {
                const embed = document.createElement('embed');
                embed.src = fileURL;
                embed.type = 'application/pdf';
                embed.className = 'w-full h-full rounded-xl';
                preview.appendChild(embed);
            } else {
                preview.innerHTML = `
                    <div class="text-center p-4">
                        <svg class="w-14 h-14 mx-auto text-amber-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-700">${file.name}</p>
                        <p class="text-xs text-gray-400 mt-1">File uploaded successfully</p>
                    </div>
                `;
            }
        });
    }
});
</script>
@endpush