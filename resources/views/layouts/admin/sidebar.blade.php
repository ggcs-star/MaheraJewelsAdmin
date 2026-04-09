<div 
    x-show="sidebarOpen" 
    class="fixed inset-0 bg-gray-900/50 z-30 md:hidden backdrop-blur-sm" 
    @click="sidebarOpen = false"
></div>

<aside 
    class="fixed top-0 left-0 bottom-0 z-40 w-[260px] bg-[#1e293b] text-white transition-transform duration-300 transform shadow-2xl overflow-y-auto"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="h-[72px] flex items-center px-5 bg-[#0f172a] border-b border-slate-700">

    <div class="flex items-center gap-3 w-full">

        <img
            src="{{ asset('assets/logo/logo.png') }}"
            alt="Radiant Jewel Logo"
            class="h-12 w-12 object-contain rounded-lg bg-white p-1"
            onerror="this.src='{{ asset('assets/logo/logo.png') }}'"
        >

        <div class="flex flex-col leading-tight">

            <span class="text-white font-semibold text-[15px] tracking-wide whitespace-nowrap">
                RADIANT
            </span>

            <span class="text-indigo-400 font-medium text-[12px] tracking-wide whitespace-nowrap">
                JEWEL
            </span>

        </div>

    </div>

</div>

    <!-- </div> -->

    <nav class="mt-8 px-4 space-y-1">

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
           {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ admin_route('suppliers.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
           {{ request()->routeIs('suppliers.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Suppliers</span>
        </a>

        <a href="{{ admin_route('categories.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <span>Categories</span>
        </a>

        <a href="{{ admin_route('products.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
           {{ request()->routeIs('products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <span>Inventory</span>
</a>


        <a href="{{ admin_route('products.list') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
           {{ request()->routeIs('products.list') || request()->routeIs('products.push') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>Product</span>
        </a>
<a href="{{ admin_route('banks.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('banks.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
    
    <i class="fas fa-university w-5 h-5 text-center"></i>
    
    <span>Bank</span>
</a>
<a href="{{ admin_route('coupons.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('coupons.*')
        ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">

    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 14l6-6m-7 0h.01M16 14h.01M5 7h14v10H5z"/>
    </svg>

    <span>Coupons</span>
</a>
<a href="{{ admin_route('customers.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('customers.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">

    <i class="fas fa-user-friends w-5 h-5 text-center"></i>

    <span>Customers</span>
</a>

<a href="{{ admin_route('invoices.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('invoices.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">

    <i class="fas fa-file-invoice w-5 h-5 text-center"></i>

    <span>Invoice</span>
</a>

<a href="{{ admin_route('banners.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('banners.*')
        ? 'bg-pink-600 text-white shadow-lg shadow-pink-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">

    <!-- Banner / Promotion Icon -->
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 8h10M7 12h6m-6 4h10M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
    </svg>

    <span>Banners</span>
</a>
<a href="{{ admin_route('reels.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('reels.*')
        ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">

    <!-- Reel Icon -->
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 4h10M7 8h10M7 12h10M5 4v16l14-8L5 4z"/>
    </svg>

    <span>Reels</span>
</a>

<a href="{{ admin_route('orders.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('orders.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">

    <!-- Order Icon -->
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h18l-2 13H5L3 3zm6 16a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4z"/>
    </svg>

    <span>Orders</span>

</a>
<a href="{{ admin_route('stock.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('stock.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 7l9-4 9 4-9 4-9-4z"/>
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 7v10l9 4 9-4V7"/>
    </svg>

    <span>Stock Management</span>

</a>
<a href="{{ admin_route('stock.settings') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('stock.settings') 
        ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/20 font-medium' 
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 16v-2m8-6h-2M6 12H4m13.657-5.657l-1.414 1.414M7.757 16.243l-1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z"/>
    </svg>
    <span>Stock Alert Settings</span>
</a>
        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6.012 18H21V8a2 2 0 0 0-2-2h-8L9 4H3a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h1.012a3 3 0 1 0 6 0m5 0h4.976a3 3 0 1 0 6 0H11z"/>
            </svg>
            <span>Marketplace</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6" />
            </svg>
            <span>Reports</span>
        </a>

      <a href="{{ admin_route('warehouses.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('warehouses.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 16v-2m8-6h-2M6 12H4m13.657-5.657l-1.414 1.414M7.757 16.243l-1.414 1.414" />
            </svg>
            <span>Settings</span>
        </a>

    </nav>
</aside>