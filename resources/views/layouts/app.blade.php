<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Lunar Commerce') - Fresh Indian Groceries & General Merchandise</title>
    <meta name="description" content="@yield('meta_description', 'Fresh farm produce, pure dairy, pantry staples, authentic spices, and wholesale bulk groceries delivered in 15 minutes or via standard delivery.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js is included with Livewire 3 -->
    @livewireStyles

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="flex min-h-full flex-col antialiased selection:bg-emerald-500 selection:text-white">

    <!-- 1. Top Announcement Bar -->
    <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 px-4 py-2 text-xs font-semibold text-white shadow-inner">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-slate-900">
                    ⚡ Quick 15-Min Delivery
                </span>
                <span class="hidden sm:inline">Free Standard Delivery on orders over €35.00 | Bulk savings up to 25% off</span>
            </div>
            <div class="flex items-center space-x-4 text-emerald-100">
                <span class="hidden md:inline">📞 Support: +44 20 7946 0991</span>
                <a href="{{ route('page.contact') }}" class="hover:text-white transition">Help Center</a>
                <a href="{{ url('/lunar') }}" target="_blank" class="rounded bg-emerald-900/60 px-2 py-0.5 text-[11px] text-emerald-200 hover:text-white hover:bg-emerald-900 transition">
                    Admin Portal &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main Navigation Header -->
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 backdrop-blur-md shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between gap-4 lg:gap-8">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-emerald-600 transition">
                                LUNAR<span class="text-emerald-600">SHOP</span>
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Grocery & Wholesale</span>
                        </div>
                    </a>

                    <!-- Pincode / Area Pill -->
                    <div class="hidden xl:flex items-center pl-3 border-l border-slate-200">
                        <button type="button" onclick="document.getElementById('pincodeModal')?.classList.toggle('hidden')" class="flex items-center gap-1.5 rounded-lg bg-slate-100 hover:bg-slate-200/80 px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ session('quick_postcode', '400001') }}</span>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1 rounded">15m</span>
                        </button>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="flex-1 max-w-xl">
                    <form action="{{ route('products.search') }}" method="GET" class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search fresh mangoes, basmati rice, amul ghee, atta, spices..." class="w-full rounded-full border border-slate-300 bg-slate-50/70 py-2.5 pl-11 pr-24 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition shadow-inner">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 rounded-full bg-emerald-600 px-4 text-xs font-bold text-white hover:bg-emerald-700 transition">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Navigation Action Links & Cart -->
                <div class="flex items-center space-x-3 sm:space-x-4">

                    <!-- Customer Account -->
                    @auth
                        <div class="relative group" x-data="{ open: false }">
                            <a href="{{ route('account.index') }}" class="flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-emerald-500 hover:text-emerald-600 transition">
                                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="hidden sm:inline">{{ Str::limit(Auth::user()->name, 12) }}</span>
                            </a>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-emerald-500 hover:text-emerald-600 transition">
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden sm:inline">Sign In</span>
                        </a>
                    @endauth

                    <!-- Cart Trigger Button -->
                    @php
                        $activeCart = \Lunar\Facades\CartSession::current();
                        $cartCount = $activeCart ? $activeCart->lines()->sum('quantity') : 0;
                        $cartTotalFormatted = $activeCart && $activeCart->total ? $activeCart->total->formatted : '€0.00';
                    @endphp
                    <a href="{{ route('cart.index') }}" class="relative flex items-center gap-2 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition group">
                        <div class="relative">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            <span id="cart-badge-counter" class="absolute -top-2 -right-2 flex h-4 w-4 items-center justify-center rounded-full bg-amber-400 text-[10px] font-black text-slate-900 shadow">
                                {{ $cartCount }}
                            </span>
                        </div>
                        <span class="hidden md:inline">{{ $cartTotalFormatted }}</span>
                    </a>
                </div>

            </div>

            <!-- 3. Three Shopping Experience Tabs -->
            <div class="flex items-center justify-between border-t border-slate-100 py-2.5 overflow-x-auto no-scrollbar">
                <nav class="flex items-center space-x-1 sm:space-x-2 text-xs font-bold">
                    <a href="{{ route('home') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('home') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Home
                    </a>
                    <a href="{{ route('experience.retail') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('experience.retail') ? 'bg-emerald-600 text-white' : 'text-slate-700 hover:text-emerald-600 hover:bg-emerald-50' }}">
                        🛒 Retail Store
                    </a>
                    <a href="{{ route('experience.bulk') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('experience.bulk') ? 'bg-amber-500 text-slate-950 font-extrabold shadow-sm' : 'text-slate-700 hover:text-amber-600 hover:bg-amber-50' }}">
                        📦 Bulk Buy Wholesale
                    </a>
                    <a href="{{ route('experience.quick') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('experience.quick') ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        ⚡ Quick Shop (15-Min)
                    </a>
                    <a href="{{ route('products.index') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('products.index') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        All Products
                    </a>
                </nav>

                <div class="hidden lg:flex items-center space-x-3 text-xs text-slate-500 font-medium">
                    <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-semibold">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Hub Online & Dispatching
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages / Toast Alerts -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-800 shadow-sm flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-20 border-t border-slate-200 bg-white">
        <!-- Trust Banners -->
        <div class="border-b border-slate-100 bg-slate-50/60 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 font-bold">⚡</div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">15-Min Quick Delivery</h4>
                            <p class="text-[11px] text-slate-500">Serviceable across key city zones</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 font-bold">📦</div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Wholesale Bulk Deals</h4>
                            <p class="text-[11px] text-slate-500">Tiered volume savings for homes & businesses</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-bold">🌿</div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Direct From Farm</h4>
                            <p class="text-[11px] text-slate-500">Strictly inspected quality produce</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700 font-bold">🛡️</div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Secure Payments</h4>
                            <p class="text-[11px] text-slate-500">COD, UPI, Razorpay & Cards</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Links Grid -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-5">
                
                <div class="col-span-2">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white font-bold text-sm">L</div>
                        <span class="text-lg font-black text-slate-900">LUNAR<span class="text-emerald-600">SHOP</span></span>
                    </div>
                    <p class="mt-3 text-xs leading-relaxed text-slate-500 max-w-sm">
                        Complete modern e-commerce experience powered by Laravel 11 and Lunar PHP. Supporting seamless retail shopping, wholesale bulk volume buying, and 15-minute quick commerce.
                    </p>
                    <div class="mt-4 flex items-center space-x-2 text-xs text-slate-400">
                        <span>Currency: <strong class="text-slate-700">EUR (€)</strong></span>
                        <span>&bull;</span>
                        <span>Market: <strong class="text-slate-700">+44 US</strong></span>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Shopping Modes</h3>
                    <ul class="mt-3 space-y-2 text-xs text-slate-600">
                        <li><a href="{{ route('experience.retail') }}" class="hover:text-emerald-600 transition">Retail Groceries</a></li>
                        <li><a href="{{ route('experience.bulk') }}" class="hover:text-emerald-600 transition">Bulk Buy Wholesale</a></li>
                        <li><a href="{{ route('experience.quick') }}" class="hover:text-emerald-600 transition">15-Min Quick Shop</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-emerald-600 transition">All 24 Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-emerald-600 transition">View Shopping Cart</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Customer Support</h3>
                    <ul class="mt-3 space-y-2 text-xs text-slate-600">
                        <li><a href="{{ route('page.contact') }}" class="hover:text-emerald-600 transition">Contact Us</a></li>
                        <li><a href="{{ route('account.orders') }}" class="hover:text-emerald-600 transition">Track Your Order</a></li>
                        <li><a href="{{ route('account.addresses') }}" class="hover:text-emerald-600 transition">Saved Addresses</a></li>
                        <li><a href="{{ route('page.about') }}" class="hover:text-emerald-600 transition">About Our Store</a></li>
                        <li><a href="{{ url('/lunar') }}" target="_blank" class="hover:text-emerald-600 transition font-semibold text-emerald-700">Admin Control Panel</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Legal & Terms</h3>
                    <ul class="mt-3 space-y-2 text-xs text-slate-600">
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-emerald-600 transition">Privacy Policy</a></li>
                        <li><a href="{{ route('page.terms') }}" class="hover:text-emerald-600 transition">Terms of Service</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-emerald-600 transition">Refund & Return Policy</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-emerald-600 transition">Delivery Serviceability</a></li>
                    </ul>
                </div>

            </div>

            <div class="mt-12 border-t border-slate-100 pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Lunar Commerce Ltd. Built with Laravel 11 & Lunar PHP.</p>
                <div class="mt-4 md:mt-0 flex items-center space-x-3">
                    <span class="rounded bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">Cash on Delivery</span>
                    <span class="rounded bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">Razorpay Online</span>
                    <span class="rounded bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">UPI / QR</span>
                    <span class="rounded bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">Cards</span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>

