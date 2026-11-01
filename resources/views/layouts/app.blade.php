<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 text-slate-800 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'A&C Electronics - Connecting Global Brands to UK Markets')</title>
    <meta name="description" content="@yield('meta_description', 'A&C Electronics is your premier gateway to global electronic brands in the UK. Direct OEM sourcing for Lenovo & AOC audio, smart wearables, and computer peripherals. Global trade, master carton wholesale, and trusted partnerships.')">

    <!-- Fonts: Outfit (Display/Headings) + Plus Jakarta Sans (Sans Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
        }
        h1, h2, h3, .font-display {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex min-h-full flex-col selection:bg-blue-600 selection:text-white"
      x-data="globalStorefrontHandler()"
      @cart-updated.window="onCartUpdated($event.detail)"
      @keydown.escape.window="closeAllModals()">

    @php
        $activeCart = \Lunar\Facades\CartSession::current();
        $cartCount = $activeCart ? $activeCart->lines()->sum('quantity') : 0;
        $cartTotalFormatted = $activeCart && $activeCart->total ? $activeCart->total->formatted : '£0.00';
        $cartLines = [];
        if ($activeCart) {
            foreach ($activeCart->lines as $line) {
                $variant = $line->purchasable;
                $product = $variant?->product;
                $metadata = $product ? \App\Models\ProductMetadata::where('product_id', $product->id)->first() : null;
                $cartLines[] = [
                    'id' => $line->id,
                    'name' => $product ? $product->translateAttribute('name') : ($variant?->sku ?? 'Electronic Device'),
                    'quantity' => $line->quantity,
                    'unit_price' => $line->unitPrice?->formatted ?? '£0.00',
                    'sub_total' => $line->subTotal?->formatted ?? '£0.00',
                    'image' => $metadata?->image_url ?? $product?->primary_image_url ?? 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
                ];
            }
        }
    @endphp

    <!-- 1. Top Announcement Bar with Animated Tagline Ticker -->
    <div class="bg-slate-950 px-4 py-2 text-[11px] font-medium tracking-wide text-slate-300 border-b border-slate-800/80">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4">
            
            <!-- Left Animated Ticker Cycling Official Taglines -->
            <div x-data="{
                    taglines: [
                        'Connecting Global Brands to UK Markets',
                        'Global Trade. Trusted Partnerships.',
                        'Powering Global Trade, Building Trust.',
                        'Your Gateway to Global Markets.',
                        'Connecting Markets, Delivering Excellence'
                    ],
                    currentIndex: 0,
                    init() {
                        setInterval(() => {
                            this.currentIndex = (this.currentIndex + 1) % this.taglines.length;
                        }, 3800);
                    }
                 }"
                 class="flex items-center space-x-2 overflow-hidden h-5">
                <span class="inline-flex h-2 w-2 relative flex-shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-400 bg-blue-950/70 border border-blue-800/50 px-2 py-0.5 rounded-full flex-shrink-0">
                    Official
                </span>
                
                <div class="relative h-5 flex items-center min-w-[260px] sm:min-w-[340px]">
                    <template x-for="(tagline, index) in taglines" :key="index">
                        <div x-show="currentIndex === index"
                             x-transition:enter="transition ease-out duration-400 transform"
                             x-transition:enter-start="opacity-0 translate-y-3"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-300 transform"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-3"
                             class="absolute inset-0 flex items-center text-xs font-semibold text-slate-100 truncate">
                            <span x-text="tagline"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Utility Links -->
            <div class="hidden sm:flex items-center space-x-4 text-[11px] text-slate-400 flex-shrink-0">
                <a href="{{ route('experience.bulk') }}" class="text-blue-400 hover:text-blue-300 transition font-semibold flex items-center gap-1">
                    <span>B2B Wholesale Portal</span>
                    <span class="text-[9px] bg-blue-600/30 text-blue-300 border border-blue-500/30 px-1.5 py-0.2 rounded">Tiered Pricing</span>
                </a>
                <span class="text-slate-700">&bull;</span>
                <a href="{{ route('page.contact') }}" class="hover:text-white transition">UK Trade Desk</a>
                <span class="text-slate-700">&bull;</span>
                <span class="inline-flex items-center gap-1 font-semibold text-slate-300">
                    <svg class="h-3 w-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    CE &amp; RoHS Certified
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Main High-Tech Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs transition duration-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between gap-4">
                
                <!-- Left: Hamburger (Mobile) + Quick Search Trigger (Desktop) -->
                <div class="flex items-center gap-3 lg:flex-1">
                    <!-- Mobile Hamburger Button -->
                    <button type="button"
                            @click="mobileMenuOpen = true"
                            class="lg:hidden p-2 -ml-2 text-slate-800 hover:text-blue-600 transition focus:outline-none"
                            aria-label="Open Navigation Menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Desktop Search Trigger Pill -->
                    <button type="button"
                            @click="searchModalOpen = true"
                            class="hidden lg:flex items-center gap-2.5 rounded-full border border-slate-200 bg-slate-50/90 px-4 py-2 text-xs text-slate-500 hover:border-blue-400 hover:bg-white hover:text-slate-800 transition shadow-2xs">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Search Lenovo EA Series, AOC Hybrid ANC, smartwatches...</span>
                        <kbd class="hidden xl:inline-block rounded bg-slate-100 border border-slate-200 px-1.5 py-0.5 text-[10px] font-mono text-slate-400">⌘K</kbd>
                    </button>
                </div>

                <!-- Center: A&C Electronics Brand Logo -->
                <div class="flex-shrink-0 text-center">
                    <a href="{{ route('home') }}" class="group inline-flex flex-col items-center">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-8 items-center justify-center rounded-lg bg-blue-600 px-2.5 text-xs sm:text-sm font-black tracking-wider text-white shadow-md group-hover:bg-blue-700 transition">
                                A&amp;C
                            </span>
                            <span class="font-display text-xl sm:text-2xl font-black tracking-tight text-slate-950 group-hover:text-blue-600 transition">
                                ELECTRONICS
                            </span>
                        </div>
                        <span class="text-[9px] font-medium tracking-[0.2em] text-slate-500 uppercase mt-0.5">
                            Connecting Global Brands to UK Markets
                        </span>
                    </a>
                </div>

                <!-- Right: Action Icons (Search, Wholesale, Account, Cart Drawer) -->
                <div class="flex items-center justify-end space-x-1 sm:space-x-3 lg:flex-1">
                    
                    <!-- Mobile Search Trigger -->
                    <button type="button"
                            @click="searchModalOpen = true"
                            class="lg:hidden p-2 text-slate-700 hover:text-blue-600 transition"
                            aria-label="Search Catalog">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <!-- Wholesale Fast Switch -->
                    <a href="{{ route('experience.bulk') }}" class="hidden md:inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50/70 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">
                        <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <span>B2B Wholesale</span>
                    </a>

                    <!-- User Account -->
                    @auth
                        <a href="{{ route('account.index') }}" class="flex items-center gap-1.5 p-2 text-slate-700 hover:text-blue-600 transition" title="My Account">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span class="hidden xl:inline text-xs font-semibold">{{ Str::limit(Auth::user()->name, 10) }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-1.5 p-2 text-slate-700 hover:text-blue-600 transition" title="Sign In">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span class="hidden xl:inline text-xs font-semibold">Sign In</span>
                        </a>
                    @endauth

                    <!-- Slide-Over Cart Drawer Trigger Button -->
                    <button type="button"
                            @click="cartDrawerOpen = true"
                            class="relative flex items-center gap-2 rounded-full bg-slate-900 px-3.5 sm:px-4 py-2 text-xs font-semibold text-white hover:bg-blue-600 transition shadow-sm group">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25c-.67 0-1.19-.578-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <span class="hidden sm:inline font-sans uppercase tracking-wider text-[11px]">Bag</span>
                        <span id="cart-badge-counter"
                              x-text="cartCount"
                              class="flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-blue-500 text-[10px] font-black text-white">
                            {{ $cartCount }}
                        </span>
                    </button>

                </div>
            </div>

            <!-- 3. Desktop Sub-Navigation Menu -->
            <nav class="hidden lg:flex items-center justify-center space-x-7 border-t border-slate-200/80 py-3 text-xs font-semibold uppercase tracking-wider text-slate-700">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition {{ request()->routeIs('home') ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-0.5' : '' }}">
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="hover:text-blue-600 transition {{ request()->routeIs('products.index') && !request('category') ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-0.5' : '' }}">
                    All Electronics
                </a>
                <a href="{{ route('products.index', ['category' => 'tws-earbuds']) }}" class="hover:text-blue-600 transition {{ request('category') === 'tws-earbuds' ? 'text-blue-600 font-bold' : '' }}">
                    TWS Wireless
                </a>
                <a href="{{ route('products.index', ['category' => 'anc-audio']) }}" class="hover:text-blue-600 transition {{ request('category') === 'anc-audio' ? 'text-blue-600 font-bold' : '' }}">
                    Hybrid ANC
                </a>
                <a href="{{ route('products.index', ['category' => 'smartwatches-wearables']) }}" class="hover:text-blue-600 transition {{ request('category') === 'smartwatches-wearables' ? 'text-blue-600 font-bold' : '' }}">
                    Smartwatches
                </a>
                <a href="{{ route('products.index', ['category' => 'computer-mice']) }}" class="hover:text-blue-600 transition {{ request('category') === 'computer-mice' ? 'text-blue-600 font-bold' : '' }}">
                    Peripherals
                </a>
                <a href="{{ route('experience.retail') }}" class="hover:text-blue-600 transition {{ request()->routeIs('experience.retail') ? 'text-blue-600 font-bold' : '' }}">
                    Retail Store
                </a>
                <a href="{{ route('experience.bulk') }}" class="text-blue-600 hover:text-blue-700 font-bold transition flex items-center gap-1">
                    <span>B2B Master Carton</span>
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                </a>
                <a href="{{ route('page.contact') }}" class="hover:text-blue-600 transition {{ request()->routeIs('page.contact') ? 'text-blue-600 font-bold' : '' }}">
                    Trade Desk
                </a>
            </nav>
        </div>
    </header>

    <!-- 4. Mobile Slide-Out Navigation Drawer -->
    <div x-cloak
         x-show="mobileMenuOpen"
         class="fixed inset-0 z-50 lg:hidden"
         role="dialog"
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs"></div>

        <!-- Drawer Panel -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-8 shadow-2xl h-full overflow-y-auto">
            
            <!-- Close Button & Brand Header -->
            <div class="flex items-center justify-between px-6 pb-4 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-7 items-center justify-center rounded-lg bg-blue-600 px-2 text-xs font-black tracking-wider text-white shadow-xs">
                        A&amp;C
                    </span>
                    <div>
                        <span class="font-display text-base font-bold text-slate-900">A&amp;C ELECTRONICS</span>
                        <p class="text-[9px] uppercase tracking-wider text-slate-500">Connecting Global Brands to UK Markets</p>
                    </div>
                </div>
                <button type="button"
                        @click="mobileMenuOpen = false"
                        class="p-2 -mr-2 text-slate-500 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Search Bar -->
            <div class="px-6 py-4">
                <form action="{{ route('products.search') }}" method="GET" class="relative">
                    <input type="text"
                           name="q"
                           placeholder="Search Lenovo, AOC, Smartwatches..."
                           class="w-full rounded-full border border-slate-300 bg-slate-50 py-2.5 pl-9 pr-4 text-xs text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Navigation Links -->
            <div class="px-6 space-y-1 text-sm font-medium text-slate-800">
                <a href="{{ route('home') }}" class="block py-2.5 border-b border-slate-100 hover:text-blue-600">
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="block py-2.5 border-b border-slate-100 hover:text-blue-600">
                    All Electronics Catalog
                </a>
                <a href="{{ route('products.index', ['category' => 'tws-earbuds']) }}" class="flex items-center justify-between py-2.5 border-b border-slate-100 hover:text-blue-600">
                    <span>TWS Wireless Earbuds</span>
                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">Lenovo &bull; AOC</span>
                </a>
                <a href="{{ route('products.index', ['category' => 'anc-audio']) }}" class="block py-2.5 border-b border-slate-100 hover:text-blue-600">
                    Active Noise Cancelling (ANC)
                </a>
                <a href="{{ route('products.index', ['category' => 'smartwatches-wearables']) }}" class="block py-2.5 border-b border-slate-100 hover:text-blue-600">
                    Smartwatches &amp; Wearables
                </a>
                <a href="{{ route('products.index', ['category' => 'computer-mice']) }}" class="block py-2.5 border-b border-slate-100 hover:text-blue-600">
                    Computer Mice &amp; Peripherals
                </a>
                <a href="{{ route('experience.retail') }}" class="block py-2.5 border-b border-slate-100 hover:text-blue-600">
                    Retail Consumer Store
                </a>
                <a href="{{ route('experience.bulk') }}" class="flex items-center justify-between py-2.5 border-b border-slate-100 text-blue-600 font-bold hover:text-blue-700">
                    <span>B2B Master Carton Portal</span>
                    <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">Save Up to 40%</span>
                </a>
                <a href="{{ route('page.about') }}" class="block py-2.5 border-b border-slate-100 hover:text-blue-600">
                    Global Trade &amp; Partnerships
                </a>
                <a href="{{ route('page.contact') }}" class="block py-2.5 border-b border-slate-100 hover:text-blue-600">
                    UK Trade Desk &amp; Inquiries
                </a>
            </div>

            <!-- Customer Account & Trade Support Footer -->
            <div class="mt-auto px-6 pt-6 border-t border-slate-200 space-y-3">
                @auth
                    <a href="{{ route('account.index') }}" class="flex items-center justify-between rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white">
                        <span>My Account ({{ Auth::user()->name }})</span>
                        <span>&rarr;</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center justify-between rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white">
                        <span>Sign In / Trade Account</span>
                        <span>&rarr;</span>
                    </a>
                @endauth
                <div class="text-[11px] text-slate-500 space-y-1">
                    <p>UK Trade Support: <strong class="text-slate-800">+44 20 7946 0991</strong></p>
                    <p>Dispatch Desk: <span class="text-slate-800 font-medium">trade@ac-electronics.co.uk</span></p>
                    <p class="text-[10px] text-blue-600 font-medium">Powering Global Trade, Building Trust.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- 5. Slide-Over Quick Cart Drawer -->
    <div x-cloak
         x-show="cartDrawerOpen"
         class="fixed inset-0 z-50"
         role="dialog"
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="cartDrawerOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="cartDrawerOpen = false"
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs"></div>

        <!-- Slide Panel -->
        <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
            <div x-show="cartDrawerOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col h-full">
                
                <!-- Drawer Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div>
                        <h2 class="font-display text-lg font-bold text-slate-900">Your Electronic Hardware Bag</h2>
                        <p class="text-xs text-slate-500"><span x-text="cartCount"></span> items selected</p>
                    </div>
                    <button type="button"
                            @click="cartDrawerOpen = false"
                            class="p-2 -mr-2 text-slate-500 hover:text-slate-900">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Free Delivery Meter -->
                <div class="px-6 py-3 bg-blue-50/80 border-b border-blue-100 text-xs text-blue-950 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-blue-700">🚚</span>
                        <span class="font-medium">Tracked Express Dispatch &bull; CE Tested</span>
                    </div>
                    <span class="font-bold text-blue-800">Verified Direct OEM ✓</span>
                </div>

                <!-- Line Items Body -->
                <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4 divide-y divide-slate-100">
                    <template x-if="cartCount == 0">
                        <div class="text-center py-16 space-y-3">
                            <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <h3 class="font-display text-base font-semibold text-slate-800">Your bag is empty</h3>
                            <p class="text-xs text-slate-500 max-w-xs mx-auto">Explore certified Lenovo audio systems, AOC Hybrid ANC headphones, and smart electronics.</p>
                            <a href="{{ route('products.index') }}"
                               @click="cartDrawerOpen = false"
                               class="inline-block mt-3 rounded-full bg-blue-600 px-6 py-2.5 text-xs font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                                Browse Electronics &rarr;
                            </a>
                        </div>
                    </template>

                    @foreach ($cartLines as $line)
                        <div class="pt-4 first:pt-0 flex gap-4 items-start">
                            <img src="{{ $line['image'] }}" alt="{{ $line['name'] }}" class="h-20 w-20 rounded-xl object-cover border border-slate-200 bg-slate-50 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 truncate leading-snug">{{ $line['name'] }}</h4>
                                <p class="text-[11px] text-blue-700 font-medium mt-0.5">OEM Certified &bull; Full UK Warranty</p>
                                <div class="mt-2 flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-900">{{ $line['unit_price'] }}</span>
                                    <span class="text-slate-500">Qty: {{ $line['quantity'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Drawer Footer Actions -->
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-5 space-y-3">
                    <div class="flex items-center justify-between text-sm font-semibold text-slate-900">
                        <span>Estimated Total:</span>
                        <span class="font-display text-lg font-bold text-blue-700" x-text="cartTotal">{{ $cartTotalFormatted }}</span>
                    </div>
                    <p class="text-[11px] text-slate-500">VAT and tracked UK / Global freight calculated at checkout.</p>
                    
                    <div class="space-y-2 pt-1">
                        <a href="{{ route('checkout.index') }}" class="block w-full rounded-full bg-blue-600 py-3 text-center text-xs font-bold uppercase tracking-wider text-white hover:bg-blue-700 transition shadow-md">
                            Proceed to Checkout &rarr;
                        </a>
                        <a href="{{ route('cart.index') }}" class="block w-full text-center text-xs font-semibold text-slate-600 hover:text-slate-900 py-1 transition">
                            View Bag &amp; Carton Matrix
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- 6. Search Overlay Modal -->
    <div x-cloak
         x-show="searchModalOpen"
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs" @click="searchModalOpen = false"></div>
        <div class="relative min-h-screen flex items-start justify-center p-4 sm:p-6 lg:p-8 pt-16 sm:pt-24">
            <div class="relative w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        <span class="font-display text-lg font-bold text-slate-900">Search Electronic Hardware</span>
                    </div>
                    <button type="button" @click="searchModalOpen = false" class="text-slate-400 hover:text-slate-700 text-lg">&times;</button>
                </div>
                <form action="{{ route('products.search') }}" method="GET" class="mt-4">
                    <div class="relative">
                        <input type="text"
                               name="q"
                               x-ref="searchInput"
                               placeholder="Search Lenovo EA Series, AOC ANC, smartwatches, mice..."
                               class="w-full rounded-full border border-slate-300 py-3 pl-12 pr-28 text-sm text-slate-900 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button type="submit" class="absolute right-2 top-2 bottom-2 rounded-full bg-blue-600 px-5 text-xs font-semibold text-white hover:bg-blue-700 transition">
                            Search
                        </button>
                    </div>
                </form>
                <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
                    <span class="font-bold text-slate-700">Popular Tech Searches:</span>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        <a href="{{ route('products.search', ['q' => 'Lenovo']) }}" class="rounded-full bg-slate-100 hover:bg-slate-200 px-3 py-1 text-[11px] text-slate-700 font-medium">Lenovo EA Series</a>
                        <a href="{{ route('products.search', ['q' => 'ANC']) }}" class="rounded-full bg-blue-100 hover:bg-blue-200 px-3 py-1 text-[11px] text-blue-800 font-semibold">AOC Hybrid ANC -40dB</a>
                        <a href="{{ route('products.search', ['q' => 'Smartwatch']) }}" class="rounded-full bg-slate-100 hover:bg-slate-200 px-3 py-1 text-[11px] text-slate-700 font-medium">AMOLED Smartwatches</a>
                        <a href="{{ route('products.search', ['q' => 'Mouse']) }}" class="rounded-full bg-slate-100 hover:bg-slate-200 px-3 py-1 text-[11px] text-slate-700 font-medium">Tri-Mode Wireless Mice</a>
                        <a href="{{ route('experience.bulk') }}" class="rounded-full bg-emerald-100 hover:bg-emerald-200 px-3 py-1 text-[11px] text-emerald-900 font-bold">Master Carton Wholesale</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. Floating Dynamic Toast Notification Pill -->
    <div x-cloak
         x-show="toast.visible"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
         class="fixed bottom-20 sm:bottom-6 right-4 sm:right-6 z-50 w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-4 shadow-xl text-slate-900">
        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-blue-700">Added to Hardware Bag</h4>
                    <button type="button" @click="toast.visible = false" class="text-slate-400 hover:text-slate-700">&times;</button>
                </div>
                <p class="mt-1 text-xs font-semibold text-slate-800 truncate" x-text="toast.title"></p>
                <div class="mt-3 flex items-center gap-2">
                    <button type="button"
                            @click="toast.visible = false; cartDrawerOpen = true"
                            class="flex-1 rounded-full bg-blue-600 py-1.5 text-center text-xs font-semibold text-white hover:bg-blue-700 transition">
                        View Bag &rarr;
                    </button>
                    <a href="{{ route('checkout.index') }}" class="flex-1 rounded-full border border-slate-300 py-1.5 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 8. Flash Session Messages -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-900 shadow-xs flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs font-semibold text-rose-900 shadow-xs flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
            </div>
        @endif
    </div>

    <!-- 9. Main Storefront Content -->
    <main class="flex-1 pb-20 md:pb-12">
        @yield('content')
    </main>

    <!-- 10. Mobile Sticky Bottom Navigation Bar (High Utility Dock) -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 shadow-lg">
        <div class="flex items-center justify-around text-center">
            
            <!-- Home -->
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-[10px] font-medium transition {{ request()->routeIs('home') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Home</span>
            </a>

            <!-- Shop / Catalog -->
            <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-1 text-[10px] font-medium transition {{ request()->routeIs('products.index') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span>Catalog</span>
            </a>

            <!-- Search -->
            <button type="button" @click="searchModalOpen = true" class="flex flex-col items-center gap-1 text-[10px] font-medium text-slate-500 hover:text-slate-900 transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Search</span>
            </button>

            <!-- Bag / Cart Drawer -->
            <button type="button" @click="cartDrawerOpen = true" class="relative flex flex-col items-center gap-1 text-[10px] font-medium text-slate-500 hover:text-slate-900 transition">
                <div class="relative">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25c-.67 0-1.19-.578-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span x-text="cartCount" class="absolute -top-1.5 -right-2 flex h-3.5 min-w-3.5 px-0.5 items-center justify-center rounded-full bg-blue-600 text-[9px] font-bold text-white"></span>
                </div>
                <span>Bag</span>
            </button>

            <!-- Account -->
            <a href="{{ Auth::check() ? route('account.index') : route('login') }}" class="flex flex-col items-center gap-1 text-[10px] font-medium transition {{ request()->routeIs('account.*') || request()->routeIs('login') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span>{{ Auth::check() ? 'Account' : 'Sign In' }}</span>
            </a>

        </div>
    </nav>

    <!-- 11. High-Tech Corporate Footer -->
    <footer class="border-t border-slate-200 bg-slate-950 text-slate-400">
        
        <!-- Trust Pillars Strip -->
        <div class="border-b border-slate-800/80 bg-slate-900/60 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-950/80 text-blue-400 border border-blue-800/40">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Direct OEM Sourcing</h4>
                            <p class="text-[11px] text-slate-400">100% genuine Lenovo &amp; AOC hardware</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-950/80 text-blue-400 border border-blue-800/40">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Master Carton Wholesale</h4>
                            <p class="text-[11px] text-slate-400">Tiered volume B2B discount tiers</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-950/80 text-blue-400 border border-blue-800/40">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V3.75a.75.75 0 00-.75-.75H3.75A.75.75 0 003 3.75v10.5c0 .414.336.75.75.75h2.25" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">UK &amp; Global Logistics</h4>
                            <p class="text-[11px] text-slate-400">Tracked air freight &amp; container pallets</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-950/80 text-blue-400 border border-blue-800/40">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Trusted Trade Escrow</h4>
                            <p class="text-[11px] text-slate-400">Secure payment &amp; bank wire options</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer Links -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-5">
                
                <!-- Col 1: Brand Info & Official Taglines -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 items-center justify-center rounded-lg bg-blue-600 px-2.5 text-xs sm:text-sm font-black tracking-wider text-white shadow-md">
                            A&amp;C
                        </span>
                        <span class="font-display text-2xl font-bold tracking-tight text-white">
                            A&amp;C ELECTRONICS
                        </span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400 max-w-sm">
                        Premier international electronic hardware distributor. We bridge certified global consumer electronic brands directly to UK retailers, wholesalers, and consumers.
                    </p>
                    
                    <!-- Official Taglines List -->
                    <div class="pt-2 space-y-1.5 border-t border-slate-800">
                        <p class="text-[10px] uppercase font-bold tracking-wider text-blue-400">Our Commitments</p>
                        <div class="grid grid-cols-1 gap-1 text-[11px] text-slate-300">
                            <span class="flex items-center gap-1.5">
                                <span class="h-1 w-1 rounded-full bg-blue-400"></span>
                                <em>Connecting Global Brands to UK Markets</em>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="h-1 w-1 rounded-full bg-blue-400"></span>
                                <em>Powering Global Trade, Building Trust.</em>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="h-1 w-1 rounded-full bg-blue-400"></span>
                                <em>Global Trade. Trusted Partnerships.</em>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="h-1 w-1 rounded-full bg-blue-400"></span>
                                <em>Your Gateway to Global Markets.</em>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="h-1 w-1 rounded-full bg-blue-400"></span>
                                <em>Connecting Markets, Delivering Excellence</em>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Electronic Hardware Categories -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Hardware Catalog</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('products.index', ['category' => 'tws-earbuds']) }}" class="hover:text-blue-400 transition">TWS Wireless Audio</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'anc-audio']) }}" class="hover:text-blue-400 transition">Active Noise Cancelling</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'open-ear-sports']) }}" class="hover:text-blue-400 transition">Open-Ear Sports Audio</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'gaming-over-ear']) }}" class="hover:text-blue-400 transition">Gaming Over-Ear Headsets</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'smartwatches-wearables']) }}" class="hover:text-blue-400 transition">Smartwatches &amp; Wearables</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'computer-mice']) }}" class="hover:text-blue-400 transition">Computer Peripherals</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'smart-scales']) }}" class="hover:text-blue-400 transition">Diagnostic Scales</a></li>
                    </ul>
                </div>

                <!-- Col 3: B2B Wholesale & Trade -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">B2B Trade &amp; Wholesale</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('experience.bulk') }}" class="hover:text-blue-400 font-semibold text-blue-300 transition">Master Carton Portal</a></li>
                        <li><a href="{{ route('experience.bulk') }}" class="hover:text-blue-400 transition">Volume Tier Matrix (5-50+)</a></li>
                        <li><a href="{{ route('experience.bulk') }}" class="hover:text-blue-400 transition">Container &amp; Pallet Freight</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-blue-400 transition">OEM Specification Sheets</a></li>
                        <li><a href="{{ route('page.about') }}" class="hover:text-blue-400 transition">Quality Assurance &amp; CE/RoHS</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Wholesale Partner Sign In</a></li>
                    </ul>
                </div>

                <!-- Col 4: Corporate & Compliance -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Trade Desk &amp; Legal</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('page.about') }}" class="hover:text-blue-400 transition">About A&amp;C Electronics</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-blue-400 transition">Contact UK Logistics Hub</a></li>
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-blue-400 transition">Privacy &amp; Data Policy</a></li>
                        <li><a href="{{ route('page.terms') }}" class="hover:text-blue-400 transition">Terms &amp; Trade Conditions</a></li>
                        <li class="pt-2 text-[11px] text-slate-400">
                            <strong>UK Headquarters:</strong><br>
                            London Logistics Gateway<br>
                            Phone: +44 20 7946 0991<br>
                            Email: trade@ac-electronics.co.uk
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright & Compliance -->
            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-slate-400">
                <div class="flex flex-wrap items-center gap-2">
                    <p>&copy; {{ date('Y') }} A&amp;C Electronics Ltd. All rights reserved.</p>
                    <span class="hidden md:inline">&bull;</span>
                    <span class="text-slate-300">Connecting Global Brands to UK Markets</span>
                    <span class="hidden md:inline">&bull;</span>
                    <span class="text-emerald-400">CE, RoHS &amp; UKCA Certified</span>
                </div>
                
                <!-- Payment & Trust Icons -->
                <div class="flex items-center space-x-2 text-[10px] font-mono">
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-slate-300">Visa</span>
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-slate-300">Mastercard</span>
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-slate-300">American Express</span>
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-slate-300">Apple Pay</span>
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-blue-400">B2B Wire</span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts

    <script>
        function globalStorefrontHandler() {
            return {
                mobileMenuOpen: false,
                cartDrawerOpen: false,
                searchModalOpen: false,
                cartCount: {{ $cartCount }},
                cartTotal: '{{ $cartTotalFormatted }}',
                toast: {
                    visible: false,
                    title: '',
                    timeout: null
                },
                closeAllModals() {
                    this.mobileMenuOpen = false;
                    this.cartDrawerOpen = false;
                    this.searchModalOpen = false;
                },
                onCartUpdated(detail) {
                    if (detail && detail.summary) {
                        this.cartCount = detail.summary.total_items;
                        this.cartTotal = detail.summary.total_formatted;
                    }
                    this.toast.title = detail.title || 'Product added to bag';
                    this.toast.visible = true;
                    this.cartDrawerOpen = true; // Auto open slide drawer on add to cart!

                    clearTimeout(this.toast.timeout);
                    this.toast.timeout = setTimeout(() => {
                        this.toast.visible = false;
                    }, 4000);
                }
            }
        }

        // Global AJAX Add-To-Cart listener for product cards and PDP
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('submit', async (e) => {
                const form = e.target.closest('form');
                if (!form || !form.action || !form.action.includes('/cart/add')) return;

                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalHtml = submitBtn ? submitBtn.innerHTML : '';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 justify-center">
                            <svg class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Adding to Bag...</span>
                        </span>
                    `;
                }

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        const card = form.closest('.group') || form.closest('main') || document;
                        const productTitle = card.querySelector('h1, h2, h3')?.innerText?.trim() || 'Electronic Device';

                        window.dispatchEvent(new CustomEvent('cart-updated', {
                            detail: {
                                summary: data.summary,
                                title: productTitle
                            }
                        }));

                        if (submitBtn) {
                            submitBtn.innerHTML = `<span>Added to Bag ✓</span>`;
                            setTimeout(() => {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalHtml;
                            }, 1800);
                        }
                    } else {
                        alert(data.message || 'Could not add item to bag. Please try again.');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalHtml;
                        }
                    }
                } catch (err) {
                    console.error('Cart add error:', err);
                    form.submit();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
