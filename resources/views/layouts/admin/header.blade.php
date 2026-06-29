<header class="fixed top-0 right-0 left-0 bg-white/95 backdrop-blur-md border-b border-gray-100 h-[64px] flex items-center justify-between px-6 md:px-8 z-20 shadow-sm transition-all duration-300" 
    :class="sidebarOpen ? 'md:left-[260px]' : 'left-0'">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-50 hover:text-[#8B2452] transition-all duration-200 group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <div class="hidden md:flex items-center gap-2">
  @php
    $segment1 = Request::segment(1); // admin
    $segment2 = Request::segment(2); // module name
    $segment3 = Request::segment(3); // sub module
    
    // Parent page mapping
    $parentName = [
        'dashboard' => 'Home',
        'suppliers' => 'Suppliers',
        'categories' => 'Categories',
        'products' => 'Inventory',
        'orders' => 'Orders',
        'banks' => 'Banks',
        'invoices' => 'Invoices',
        'coupons' => 'Coupons',
        'banners' => 'Banners',
        'reels' => 'Reels',
        'customers' => 'Customers',
        'stock' => 'Stock',
        'warehouses' => 'Settings',
        'profile' => 'Profile',
        'variants' => 'Variants',
        'organizations' => 'Organizations',
        'delivery-settings' => 'Delivery',
        'app-settings' => 'App',
    ][$segment2] ?? 'Pages';
    
    // Page title mapping
    $pageTitle = [
        'dashboard' => 'Dashboard',
        'suppliers' => 'Supplier Management',
        'categories' => 'Category Management',
        'products' => 'Product Inventory',
        'orders' => 'Order Management',
        'banks' => 'Bank Accounts',
        'invoices' => 'Invoice Management',
        'coupons' => 'Coupon Management',
        'banners' => 'Banner Management',
        'reels' => 'Reel Management',
        'customers' => 'Customer Management',
        'stock' => 'Stock Control',
        'warehouses' => 'System Settings',
        'profile' => 'My Profile',
        'variants' => 'Variant Management',
        'organizations' => 'Organization Management',
        'delivery-settings' => 'Delivery Settings',
        'app-settings' => 'App Configuration',
    ][$segment2] ?? 'Control Panel';
    
    // Sub page overrides
    if ($segment2 == 'products' && $segment3 == 'list') {
        $pageTitle = 'Products List';
    } elseif ($segment2 == 'products' && $segment3 == 'push') {
        $pageTitle = 'Push Products';
    } elseif ($segment2 == 'stock' && $segment3 == 'settings') {
        $pageTitle = 'Stock Alert Settings';
    } elseif ($segment2 == 'stock' && $segment3 == 'create') {
        $pageTitle = 'Add Stock';
    } elseif ($segment2 == 'suppliers' && $segment3 == 'create') {
        $pageTitle = 'Add Supplier';
    } elseif ($segment2 == 'categories' && $segment3 == 'create') {
        $pageTitle = 'Add Category';
    } elseif ($segment2 == 'products' && $segment3 == 'create') {
        $pageTitle = 'Add Product';
    } elseif ($segment2 == 'orders' && $segment3 == 'create') {
        $pageTitle = 'Create Order';
    } elseif ($segment2 == 'customers' && $segment3 == 'create') {
        $pageTitle = 'Add Customer';
    } elseif ($segment2 == 'coupons' && $segment3 == 'create') {
        $pageTitle = 'Add Coupon';
    } elseif ($segment2 == 'banners' && $segment3 == 'create') {
        $pageTitle = 'Add Banner';
    } elseif ($segment2 == 'reels' && $segment3 == 'create') {
        $pageTitle = 'Add Reel';
    } elseif ($segment2 == 'banks' && $segment3 == 'create') {
        $pageTitle = 'Add Bank';
    } elseif ($segment2 == 'invoices' && $segment3 == 'create') {
        $pageTitle = 'Create Invoice';
    } elseif ($segment2 == 'variants' && $segment3 == 'create') {
        $pageTitle = 'Add Variant';
    } elseif ($segment2 == 'organizations' && $segment3 == 'create') {
        $pageTitle = 'Add Organization';
    } elseif ($segment2 == 'delivery-settings' && $segment3 == 'create') {
        $pageTitle = 'Add Delivery Setting';
    } elseif ($segment2 == 'app-settings' && $segment3 == 'create') {
        $pageTitle = 'Add App Setting';
    } elseif ($segment2 == 'variants' && $segment3 == 'edit') {
        $pageTitle = 'Edit Variant';
    } elseif ($segment2 == 'organizations' && $segment3 == 'edit') {
        $pageTitle = 'Edit Organization';
    } elseif ($segment2 == 'delivery-settings' && $segment3 == 'edit') {
        $pageTitle = 'Edit Delivery Setting';
    } elseif ($segment2 == 'app-settings' && $segment3 == 'edit') {
        $pageTitle = 'Edit App Setting';
    }
    
    // Edit page handling for all modules
    if ($segment3 == 'edit' && !isset($pageTitle)) {
        $pageTitle = 'Edit ' . rtrim($parentName, 's');
    }
@endphp
    
    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">{{ $parentName }}</span>
    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    <span class="text-sm font-semibold text-[#8B2452]">{{ $pageTitle }}</span>
</div>
    </div>

    <div class="flex items-center gap-2">
        <button class="relative p-2 text-gray-500 hover:text-[#8B2452] hover:bg-[#8B2452]/5 rounded-lg transition-all duration-200">
        <a
    href="{{ route('admin.notifications.index') }}"
    class="relative p-2 text-gray-500 hover:text-[#8B2452]"
>
    <svg class="w-5 h-5">
        ....
    </svg>

    @php
        $count = \App\Models\Notification::where(
            'user_id',
            auth()->id()
        )
        ->where('is_read', false)
        ->count();
    @endphp

    @if($count)
        <span
            class="absolute top-1 right-1
                   bg-red-500 text-white
                   rounded-full text-xs
                   min-w-[18px] h-[18px]
                   flex items-center justify-center"
        >
            {{ $count }}
        </span>
    @endif
</a>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#8B2452] rounded-full border-2 border-white"></span>
        </button>

        <div class="relative" x-data="{ profileOpen: false }">
            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-3 p-1 rounded-full hover:bg-gray-50 transition-all duration-200 border border-transparent hover:border-gray-200">
                @php
                    $profileImg = Auth::user()->profile_image;
                    $hasValidImage = false;
                    $imageSrc = null;
                    
                    if ($profileImg) {
                        if (filter_var($profileImg, FILTER_VALIDATE_URL)) {
                            $hasValidImage = true;
                            $imageSrc = $profileImg;
                        } elseif (file_exists(public_path('storage/' . $profileImg))) {
                            $hasValidImage = true;
                            $imageSrc = asset('storage/' . $profileImg);
                        }
                    }
                @endphp
                
                @if($hasValidImage && $imageSrc)
                    <div class="w-9 h-9 rounded-full overflow-hidden shadow-sm bg-gray-100">
                        <img src="{{ $imageSrc }}" class="w-full h-full object-cover" alt="Profile">
                    </div>
                @else
                    <div class="w-9 h-9 rounded-full flex items-center justify-center bg-[#8B2452] text-white font-bold text-sm shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif
                
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-gray-800 leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Administrator</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="profileOpen" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 mb-1">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">My Account</p>
                </div>
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-[#8B2452]/5 hover:text-[#8B2452] transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile Settings
                </a>
                <a href="{{ admin_route('warehouses.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-[#8B2452]/5 hover:text-[#8B2452] transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    System Settings
                </a>
                <div class="h-px bg-gray-100 my-1"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>