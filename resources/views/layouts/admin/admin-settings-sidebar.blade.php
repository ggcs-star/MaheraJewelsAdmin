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
            <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6V4m0 16v-2m8-6h-2M6 12H4" />
                </svg>
            </div>
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

</nav>

</aside>
