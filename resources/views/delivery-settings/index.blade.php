@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Delivery Settings</h1>
            <p class="text-sm text-gray-500 mt-0.5">Configure delivery and platform fees</p>
        </div>
        <a href="{{ admin_route('delivery-settings.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Setting
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" id="deliveryFilterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" id="deliverySearch" class="w-full pl-10 pr-8 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Delivery / Platform / Tax..." value="{{ request('search') }}" autocomplete="off">
                            <span id="clearDeliverySearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer text-sm" style="display:none;">✕</span>
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="openDeliveryFilterSidebar" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="selectAllDelivery" class="w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20">
                <span class="text-sm font-medium text-gray-600">Select All</span>
            </div>
            <button type="button" id="bulkDeleteDeliveryBtn" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Selected
            </button>
        </div>

        <form method="POST" action="{{ admin_route('delivery-settings.bulk-delete') }}" id="deliveryBulkDeleteForm">
            @csrf
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Delivery Fee</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Platform Fee</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Tax %</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Free Delivery Above</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($settings as $setting)
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location='{{ admin_route('delivery-settings.show', $setting) }}'">
                        <td class="px-4 py-3" onclick="event.stopPropagation()">
                            <input type="checkbox" class="delivery-row-checkbox w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20" name="ids[]" value="{{ $setting->id }}" form="deliveryBulkDeleteForm">
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-500">{{ $setting->id }}</td>
                        <td class="px-4 py-3">
                            <span class="text-base font-bold text-gray-800">₹{{ number_format($setting->delivery_fee, 2) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-base font-bold text-gray-800">₹{{ number_format($setting->platform_fee, 2) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-base font-bold text-gray-800">{{ $setting->tax_percent }}%</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($setting->free_delivery_above > 0)
                                <span class="text-base font-bold text-emerald-600">₹{{ number_format($setting->free_delivery_above, 2) }}</span>
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ admin_route('delivery-settings.edit', $setting) }}" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ admin_route('delivery-settings.destroy', $setting) }}" method="POST" onsubmit="return confirm('Delete this record?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 20h12M6 4h12M5 8h14M5 12h14M5 16h14" />
                            </svg>
                            <p class="text-gray-500 text-sm font-medium">No records found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($settings->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
            {{ $settings->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<div id="deliveryFilterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header">
        <h5 class="mb-0 font-bold text-gray-800">Advanced Filter</h5>
        <button type="button" id="closeDeliveryFilterSidebar" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
    </div>
    <div class="filter-sidebar-body">
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Field</label>
            <select id="advField" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="delivery_fee">Delivery Fee</option>
                <option value="platform_fee">Platform Fee</option>
                <option value="tax_percent">Tax</option>
                <option value="free_delivery_above">Free Delivery</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Condition</label>
            <select id="advCondition" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equals</option>
                <option value="starts_with">Starts With</option>
                <option value="ends_with">Ends With</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Value</label>
            <input type="text" id="advValue" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Enter value...">
        </div>
        <button type="button" id="applyDeliveryAdvancedFilter" class="w-full py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm" style="background: var(--primary-light); color: white;">Apply Filter</button>
        <button type="button" id="clearDeliveryAdvancedFilter" class="w-full mt-2 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">Clear Filter</button>
    </div>
</div>

<style>
.filter-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 400px;
    height: 100%;
    background: white;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 1000;
    box-shadow: -4px 0 20px rgba(0,0,0,0.1);
}
.filter-sidebar.show {
    transform: translateX(0);
}
.filter-sidebar-header {
    padding: 18px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.filter-sidebar-body {
    padding: 24px;
}
</style>

<script>
let searchInput = document.getElementById("deliverySearch");
let clearSearch = document.getElementById("clearDeliverySearch");
let form = document.getElementById("deliveryFilterForm");

if(searchInput.value.length > 0){
    clearSearch.style.display = "block";
}

searchInput.addEventListener("keyup", function(){
    if(this.value.length > 0){
        clearSearch.style.display = "block";
    } else {
        clearSearch.style.display = "none";
    }
    setTimeout(() => form.submit(), 400);
});

clearSearch.onclick = function(){
    searchInput.value = "";
    form.submit();
}

document.getElementById("openDeliveryFilterSidebar").onclick = function(){
    document.getElementById("deliveryFilterSidebar").classList.add("show");
}

document.getElementById("closeDeliveryFilterSidebar").onclick = function(){
    document.getElementById("deliveryFilterSidebar").classList.remove("show");
}

document.getElementById("applyDeliveryAdvancedFilter").onclick = function(){
    let field = document.getElementById("advField").value;
    let cond = document.getElementById("advCondition").value;
    let value = document.getElementById("advValue").value;
    let url = new URL(window.location.href);
    url.searchParams.set("adv_field", field);
    url.searchParams.set("adv_condition", cond);
    url.searchParams.set("adv_value", value);
    window.location = url;
}

document.getElementById("clearDeliveryAdvancedFilter").onclick = function(){
    let url = new URL(window.location.href);
    url.searchParams.delete("adv_field");
    url.searchParams.delete("adv_condition");
    url.searchParams.delete("adv_value");
    window.location = url;
}
</script>
@endsection