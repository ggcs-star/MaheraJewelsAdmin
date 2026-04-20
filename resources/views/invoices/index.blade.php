@extends('layouts.admin')

@section('content')
@if(request()->has('print'))
<script>
    window.onload = () => window.print();
</script>
@endif

@if(request()->has('download'))
<script>
    window.onload = () => {
        window.print();
        document.title = "Invoice-{{ request()->route('invoice') ?? 'download' }}";
    };
</script>
@endif

<div class="space-y-4">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manage Invoice</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manage your invoice</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ admin_route('invoices.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            New Invoice
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100">
        <form id="invoiceFilterForm" method="GET" action="{{ admin_route('invoices.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Start Date</label>
                    <input type="date" name="start_date" id="startDate" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">End Date</label>
                    <input type="date" name="end_date" id="endDate" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" id="invoiceSearch" value="{{ request('search') }}" class="w-full pl-10 pr-8 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Invoice / Customer">
                        <span id="clearSearch" style="display:none; position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#888;">×</span>
                    </div>
                </div>
                <div class="flex items-end">
                    <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Find
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">SL.</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice No</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer Name</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Amount</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-36">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoices as $index => $invoice)
                <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location='{{ admin_route('invoices.show', $invoice->id) }}'">
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $invoices->firstItem() + $index }}</td>
                    <td class="px-4 py-3"><span class="text-sm font-bold text-gray-800">{{ $invoice->invoice_number }}</span></td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->customer_name ?? 'Walking Customer' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 text-right text-sm font-bold text-gray-800">₹{{ number_format($invoice->grand_total, 2) }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ admin_route('invoices.edit', $invoice->id) }}" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50" onclick="event.stopPropagation()" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <a href="{{ admin_route('invoices.show', $invoice->id) }}?print=1" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50" target="_blank" onclick="event.stopPropagation()" title="Print">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                            </a>
                            <a href="{{ admin_route('invoices.show', $invoice->id) }}?download=1" class="p-1.5 text-gray-400 hover:text-emerald-600 transition-colors rounded-lg hover:bg-emerald-50" target="_blank" onclick="event.stopPropagation()" title="Download">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 text-sm font-medium">No invoices found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-100">
                <tr>
                    <td colspan="4" class="px-4 py-3 text-right text-sm font-bold text-gray-700">Total:</td>
                    <td class="px-4 py-3 text-right text-base font-bold text-[#8B2452]">₹{{ number_format($totalAmount, 2) }}</td>
                    <td class="px-4 py-3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="mt-4 flex justify-end">
    {{ $invoices->links() }}
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form      = document.getElementById('invoiceFilterForm');
    const search    = document.getElementById('invoiceSearch');
    const clear     = document.getElementById('clearSearch');
    const startDate = document.getElementById('startDate');
    const endDate   = document.getElementById('endDate');

    if (!form || !search) return;

    let timer;
    const delay = 400;

    const submitOrReset = () => {
        const hasValue = search.value.trim() || startDate?.value || endDate?.value;
        if (!hasValue) {
            window.location = form.action;
        } else {
            form.submit();
        }
    };

    search.addEventListener('input', () => {
        clear.style.display = search.value ? 'block' : 'none';
        clearTimeout(timer);
        timer = setTimeout(() => {
            submitOrReset();
        }, delay);
    });

    clear?.addEventListener('click', () => {
        search.value = '';
        if (startDate) startDate.value = '';
        if (endDate) endDate.value = '';
        clear.style.display = 'none';
        submitOrReset();
    });

    startDate?.addEventListener('change', submitOrReset);
    endDate?.addEventListener('change', submitOrReset);

    form.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    if (search.value || startDate?.value || endDate?.value) {
        clear.style.display = 'block';
    }
});
</script>
@endpush