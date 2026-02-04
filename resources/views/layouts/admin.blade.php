<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Inventory Management</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
    <script src="{{ asset('assets/js/admin/categories.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900" x-data="{ sidebarOpen: true }">
    <div class="min-h-screen flex">
       
        @if(request()->routeIs('warehouses.*'))
    @include('layouts.admin.admin-settings-sidebar')
@else
    @include('layouts.admin.sidebar')
@endif

        
        <div 
            class="flex-1 flex flex-col transition-all duration-300"
            :class="sidebarOpen ? 'md:ml-[260px]' : 'ml-0'"
        >
           
            @include('layouts.admin.header')

           
            <main class="p-4 md:p-8 mt-[64px] flex-grow">
                @yield('content')
                @yield('scripts')
            </main>
            
            <footer class="p-4 text-center text-sm text-gray-500 border-t bg-white">
                &copy; {{ date('Y') }} Inventory Management System. Built for Administrators.
            </footer>
        </div>
    </div>
    @stack('scripts')
</body>
</html>