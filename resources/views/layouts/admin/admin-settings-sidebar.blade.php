{{-- SIDEBAR OVERLAY --}}
<div 
    x-show="sidebarOpen" 
    class="fixed inset-0 bg-gray-900/50 z-30 md:hidden backdrop-blur-sm" 
    @click="sidebarOpen = false"
    x-cloak
></div>

<aside 
    class="fixed top-0 left-0 bottom-0 z-40 w-[260px] bg-[#1e293b] text-white transition-transform duration-300 transform shadow-2xl overflow-y-auto"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    {{-- HEADER (EXACT SAME HEIGHT & STYLE) --}}
   <div class="h-[64px] flex items-center px-6 bg-[#0f172a]/50">
    <div class="flex items-center gap-3">
        <img
            src="https://www.ggconsultancy.services/assets/rapid-e140fd75.svg"
            alt="Rapid Retail Logo"
            class="h-10 w-10 object-contain"
        >

        <span class="font-bold text-lg tracking-tight uppercase">
            SETTINGS
        </span>
    </div>
</div>

<nav class="mt-8 px-4 space-y-1">

    {{-- 🔙 BACK TO DASHBOARD --}}
    <a href="{{ admin_route('dashboard') }}"
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
       {{ request()->routeIs('dashboard')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
            : 'text-white hover:bg-slate-800 hover:text-slate-100' }}">

        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>

        <span class="text-white">Back to Dashboard</span>
    </a>

    {{-- 🏬 WAREHOUSES --}}
    <a href="{{ admin_route('warehouses.index') }}"
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
       {{ request()->routeIs('warehouses.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
            : 'text-white hover:bg-slate-800 hover:text-slate-100' }}">

        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
        </svg>

        <span class="text-white">Warehouses</span>
    </a>
    {{-- 🧩 VARIANTS --}}
<a href="{{ admin_route('variants.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('variants.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-white hover:bg-slate-800 hover:text-slate-100' }}">

    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4v16m8-8H4" />
    </svg>

    <span class="text-white">Variants</span>
</a>
<a href="{{ admin_route('organizations.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('organizations.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-white hover:bg-slate-800 hover:text-slate-100' }}">

    <!-- 🏢 Organization / Office Icon -->
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />
    </svg>

    <span>Organizations</span>
</a>
<a href="{{ admin_route('delivery-settings.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('delivery-settings.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-white hover:bg-slate-800 hover:text-slate-100' }}">

    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 10h11M9 21V3m12 7l-3 3m0 0l-3-3m3 3V3" />
    </svg>

    <span class="text-white">Delivery Settings</span>
</a>
<a href="{{ admin_route('app-settings.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
   {{ request()->routeIs('app-settings.*')
        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-medium'
        : 'text-white hover:bg-slate-800 hover:text-slate-100' }}">

    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0-6v2m0 16v2m10-10h-2M4 12H2m15.66 6.34l-1.41-1.41M6.34 6.34 4.93 4.93m12.73 0-1.41 1.41M6.34 17.66l-1.41 1.41"/>
    </svg>

    <span>App Settings</span>
</a>
</nav>

</aside>
