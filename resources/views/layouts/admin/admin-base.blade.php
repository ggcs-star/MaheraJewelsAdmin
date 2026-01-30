<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50" 
      x-data="{ sidebarOpen: false }"
      x-init="$store.sidebar = { 
        isOpen: sidebarOpen,
        toggle() { 
          this.isOpen = !this.isOpen;
          sidebarOpen = this.isOpen;
        }
      }">

    {{-- SIDEBAR OVERLAY --}}
    <div 
        x-show="$store.sidebar.isOpen" 
        class="fixed inset-0 bg-gray-900/50 z-30 md:hidden backdrop-blur-sm" 
        @click="$store.sidebar.isOpen = false"
        x-cloak
    ></div>

    {{-- CONDITIONAL SIDEBAR --}}
    @if(request()->routeIs('settings.*') || 
        request()->routeIs('admin.settings.*') || 
        request()->routeIs('warehouses.*') || 
        request()->routeIs('users.*') ||
        request()->routeIs('roles.*') ||
        request()->routeIs('logs.*'))
        
        {{-- SETTINGS SIDEBAR --}}
        @include('layouts.admin.admin-settings-sidebar')
        
    @else
        
        {{-- MAIN SIDEBAR --}}
        @include('layouts.admin.sidebar')
        
    @endif

    {{-- MAIN CONTENT AREA --}}
    <div 
        class="min-h-screen transition-all duration-300"
        :class="$store.sidebar.isOpen ? 'md:ml-[260px]' : 'ml-0'"
    >
        {{-- TOP HEADER --}}
        @include('layouts.admin.header')

        {{-- PAGE CONTENT --}}
        <main class="p-4 md:p-6 lg:p-8 mt-16">
            @yield('content')
        </main>
    </div>

    {{-- MOBILE MENU TOGGLE BUTTON --}}
    <button 
        @click="$store.sidebar.toggle()"
        class="fixed bottom-4 right-4 z-50 md:hidden bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition-colors"
        x-cloak
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    @stack('scripts')
    @yield('scripts')
</body>
</html>