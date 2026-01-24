<div 
    x-show="sidebarOpen" 
    class="fixed inset-0 bg-gray-900/50 z-30 md:hidden backdrop-blur-sm" 
    @click="sidebarOpen = false"
></div>

<aside 
    class="fixed top-0 left-0 bottom-0 z-40 w-[260px] bg-[#1e293b] text-white transition-transform duration-300 transform shadow-2xl overflow-y-auto"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="h-[64px] flex items-center px-6 bg-[#0f172a]/50">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                </svg>
            </div>
            <span class="font-bold text-lg tracking-tight uppercase">INVENTORY <span class="text-indigo-400">PRO</span></span>
        </div>
    </div>

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

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>Products</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6M4 5h16v14H4z" />
            </svg>
            <span>Stock Management</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <span>Orders</span>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 14a4 4 0 016.879 3.804" />
            </svg>
            <span>Users</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6" />
            </svg>
            <span>Reports</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 16v-2m8-6h-2M6 12H4m13.657-5.657l-1.414 1.414M7.757 16.243l-1.414 1.414" />
            </svg>
            <span>Settings</span>
        </a>

    </nav>

    <div class="absolute bottom-8 left-4 right-4 p-4 rounded-2xl bg-indigo-600/10 border border-indigo-500/20">
        <p class="text-xs text-indigo-300 font-semibold mb-2 uppercase">Storage Usage</p>
        <div class="w-full h-2 bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full bg-indigo-500 w-[75%] rounded-full"></div>
        </div>
        <p class="text-[10px] text-slate-400 mt-2 uppercase tracking-wide">750 / 1000 Items (75%)</p>
    </div>
</aside>