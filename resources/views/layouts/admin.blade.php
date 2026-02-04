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
<!-- UI Toast Notification -->
<div id="uiToast" class="ui-toast"></div>

<style>
.ui-toast{
    position: fixed;
    right: 20px;   /* 🔥 change */
    top: 20px;

    background: #ef4444;
    color: #fff;
    padding: 14px 18px;
    border-radius: 12px;
    font-weight: 600;
    box-shadow: 0 15px 30px rgba(0,0,0,.2);
    display: none;
    z-index: 9999;
    animation: slideIn .35s ease;
}
@keyframes slideIn{
    from{transform: translateX(-30px); opacity:0}
    to{transform: translateX(0); opacity:1}
}
</style>

        <script>
window.showToast = function(message, type="error") {

    const toast = document.getElementById("uiToast");
    if (!toast) return;

    toast.innerText = message;

    if(type==="success"){
        toast.style.background="#16a34a";
    } else if(type==="warning"){
        toast.style.background="#f59e0b";
    } else {
        toast.style.background="#ef4444";
    }

    toast.style.display="block";

    setTimeout(()=>{
        toast.style.display="none";
    },3500);
}
</script>

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