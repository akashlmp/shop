<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'A&C Electronics') - Connecting Markets, Delivering Excellence</title>
    <meta name="description" content="@yield('meta_description', 'A&C Electronics - Official B2B wholesale and retail distributor for Lenovo, AOC audio, ANC headphones, smartwatches, and computer peripherals. Connecting Markets, Delivering Excellence.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex min-h-full flex-col antialiased selection:bg-indigo-600 selection:text-white"
      x-data="globalCartHandler()"
      @cart-updated.window="onCartUpdated($event.detail)">

    <!-- 1. Top Global Trade Bar -->
    <div class="bg-slate-950 px-4 py-2 text-xs font-medium text-slate-300 border-b border-slate-800">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center rounded-md bg-indigo-500/20 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-indigo-400 border border-indigo-500/30">
                    B2B & Retail Trade
                </span>
                <span class="hidden sm:inline text-slate-400">
                    Lenovo & AOC Certified Electronic Hardware | Master Carton Tiered Pricing | CE & RoHS Verified
                </span>
            </div>
            <div class="flex items-center space-x-4 text-xs">
                <span class="hidden md:inline text-slate-400">Global Logistics: +44 20 7946 0991</span>
                <a href="{{ route('page.contact') }}" class="hover:text-white transition">Trade Desk</a>
                <a href="{{ url('/admin') }}" target="_blank" class="rounded bg-indigo-600/30 px-2 py-0.5 text-[11px] font-bold text-indigo-300 hover:text-white hover:bg-indigo-600 transition">
                    Admin Portal &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main Navigation Header -->
    <header class="sticky top-0 z-40 border-b border-slate-800 bg-slate-900/95 backdrop-blur-md shadow-lg shadow-black/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between gap-4 lg:gap-8">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-black tracking-tight text-white group-hover:text-cyan-400 transition">
                                A&amp;C <span class="text-cyan-400">ELECTRONICS</span>
                            </span>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">
                                Connecting Markets, Delivering Excellence
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Search Form -->
                <div class="flex-1 max-w-xl">
                    <form action="{{ route('products.search') }}" method="GET" class="relative">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Search Lenovo TWS, AOC ANC headphones, smartwatches, mice, scales..."
                               class="w-full rounded-full border border-slate-700 bg-slate-800/90 py-2.5 pl-11 pr-24 text-sm text-white placeholder-slate-400 focus:border-indigo-500 focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 transition shadow-inner">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 rounded-full bg-indigo-600 px-4 text-xs font-bold text-white hover:bg-indigo-500 transition shadow">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Action Links & Cart -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <!-- Customer Account -->
                    @auth
                        <a href="{{ route('account.index') }}" class="flex items-center gap-1.5 rounded-xl border border-slate-700 bg-slate-800/80 px-3 py-2 text-xs font-semibold text-slate-200 hover:border-indigo-500 hover:text-white transition">
                            <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden sm:inline">{{ Str::limit(Auth::user()->name, 12) }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-1.5 rounded-xl border border-slate-700 bg-slate-800/80 px-3 py-2 text-xs font-semibold text-slate-200 hover:border-indigo-500 hover:text-white transition">
                            <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    <a href="{{ route('cart.index') }}" class="relative flex items-center gap-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-2 text-xs font-bold text-white shadow-md shadow-indigo-600/30 hover:brightness-110 transition group">
                        <div class="relative">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            <span id="cart-badge-counter"
                                  x-text="cartCount"
                                  class="absolute -top-2.5 -right-2.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-cyan-400 text-[10px] font-black text-slate-950 shadow">
                                {{ $cartCount }}
                            </span>
                        </div>
                        <span id="cart-total-display" x-text="cartTotal" class="hidden md:inline">{{ $cartTotalFormatted }}</span>
                    </a>
                </div>

            </div>

            <!-- 3. Navigation Bar (Retail, Bulk Buy, Categories) -->
            <div class="flex items-center justify-between border-t border-slate-800 py-2.5 overflow-x-auto no-scrollbar">
                <nav class="flex items-center space-x-1 sm:space-x-2 text-xs font-bold">
                    <a href="{{ route('home') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('home') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        Home
                    </a>
                    <a href="{{ route('experience.retail') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('experience.retail') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-indigo-400 hover:bg-slate-800' }}">
                        🛍️ Retail Store
                    </a>
                    <a href="{{ route('experience.bulk') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('experience.bulk') ? 'bg-amber-400 text-slate-950 font-extrabold shadow-sm' : 'text-amber-400 hover:text-white hover:bg-amber-400/20' }}">
                        📦 Bulk Wholesale (B2B)
                    </a>
                    <a href="{{ route('products.index') }}" class="rounded-lg px-3 py-1.5 transition {{ request()->routeIs('products.index') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        All Electronics (30+)
                    </a>
                    <a href="{{ route('products.category', 'tws-earbuds') }}" class="rounded-lg px-3 py-1.5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition">
                        TWS Earbuds
                    </a>
                    <a href="{{ route('products.category', 'anc-audio') }}" class="rounded-lg px-3 py-1.5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition">
                        ANC Audio
                    </a>
                    <a href="{{ route('products.category', 'gaming-over-ear') }}" class="rounded-lg px-3 py-1.5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition">
                        Gaming Headsets
                    </a>
                    <a href="{{ route('products.category', 'smartwatches-wearables') }}" class="rounded-lg px-3 py-1.5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition">
                        Wearables
                    </a>
                    <a href="{{ route('products.category', 'computer-mice') }}" class="rounded-lg px-3 py-1.5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition">
                        Computer Mice
                    </a>
                    <a href="{{ route('products.category', 'smart-scales') }}" class="rounded-lg px-3 py-1.5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition">
                        Smart Scales
                    </a>
                </nav>

                <div class="hidden xl:flex items-center space-x-3 text-xs text-slate-400 font-medium">
                    <span class="inline-flex items-center gap-1.5 text-cyan-300 bg-cyan-950/60 border border-cyan-800/40 px-2.5 py-1 rounded-full font-semibold">
                        <span class="h-1.5 w-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                        Direct Factory Sourcing
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Floating AJAX Dynamic Toast Notification -->
    <div x-cloak
         x-show="toast.visible"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
         class="fixed bottom-5 right-5 z-50 w-full max-w-sm rounded-2xl border border-slate-700 bg-slate-900/95 p-4 text-white shadow-2xl backdrop-blur-md">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-400">Added to Cart</h4>
                    <button type="button" @click="toast.visible = false" class="text-slate-400 hover:text-white">&times;</button>
                </div>
                <p class="mt-1 text-sm font-semibold text-slate-100 line-clamp-1" x-text="toast.title"></p>
                <div class="mt-2 flex items-center justify-between text-xs text-slate-300 border-t border-slate-800 pt-2">
                    <span>Cart Total: <strong class="text-white" x-text="cartTotal"></strong></span>
                    <a href="{{ route('cart.index') }}" class="font-bold text-indigo-400 hover:text-indigo-300 underline">
                        View Cart &rarr;
                    </a>
                </div>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('checkout.index') }}" class="flex-1 rounded-xl bg-indigo-600 py-1.5 text-center text-xs font-bold text-white hover:bg-indigo-500 transition shadow">
                        Checkout Now
                    </a>
                    <button type="button" @click="toast.visible = false" class="rounded-xl border border-slate-700 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-800 transition">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

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
    <footer class="mt-20 border-t border-slate-800 bg-slate-950 text-slate-400">
        <!-- Trust Banners -->
        <div class="border-b border-slate-800/80 bg-slate-900/60 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-400 font-bold border border-indigo-500/30">⚡</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Direct Factory Sourcing</h4>
                            <p class="text-[11px] text-slate-400">Certified Lenovo & AOC Hardware</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/20 text-amber-400 font-bold border border-amber-500/30">📦</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Master Carton Wholesale</h4>
                            <p class="text-[11px] text-slate-400">Tiered pricing for B2B exporters</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-500/20 text-cyan-400 font-bold border border-cyan-500/30">🌐</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Global Tracked Logistics</h4>
                            <p class="text-[11px] text-slate-400">Air express & heavy cargo freight</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-400 font-bold border border-emerald-500/30">🛡️</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Trade Escrow & Guarantee</h4>
                            <p class="text-[11px] text-slate-400">COD, Razorpay & International Wire</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Links Grid -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-5">
                
                <div class="col-span-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white font-black text-sm">
                            N
                        </div>
                        <span class="text-lg font-black text-white">A&amp;C <span class="text-cyan-400">ELECTRONICS</span></span>
                    </div>
                    <p class="mt-3 text-xs leading-relaxed text-slate-400 max-w-sm">
                        Premier international electronics trade platform. Empowering retail buyers and commercial wholesale distributors with certified hardware, tiered carton pricing, and seamless global fulfillment.
                    </p>
                    <p class="mt-2 text-xs font-semibold text-indigo-400 italic">
                        Connecting Markets, Delivering Excellence
                    </p>
                    <div class="mt-4 flex items-center space-x-2 text-xs text-slate-500">
                        <span>Currency: <strong class="text-slate-300">EUR (€)</strong></span>
                        <span>&bull;</span>
                        <span>Trade Desk: <strong class="text-slate-300">+44 20 7946 0991</strong></span>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200">Catalog</h3>
                    <ul class="mt-3 space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('products.category', 'tws-earbuds') }}" class="hover:text-cyan-400 transition">TWS Earbuds</a></li>
                        <li><a href="{{ route('products.category', 'anc-audio') }}" class="hover:text-cyan-400 transition">Active Noise Cancelling</a></li>
                        <li><a href="{{ route('products.category', 'gaming-over-ear') }}" class="hover:text-cyan-400 transition">Gaming Headsets</a></li>
                        <li><a href="{{ route('products.category', 'smartwatches-wearables') }}" class="hover:text-cyan-400 transition">Smartwatches</a></li>
                        <li><a href="{{ route('products.category', 'computer-mice') }}" class="hover:text-cyan-400 transition">Computer Mice</a></li>
                        <li><a href="{{ route('products.category', 'smart-scales') }}" class="hover:text-cyan-400 transition">Diagnostic Scales</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200">B2B & Account</h3>
                    <ul class="mt-3 space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('experience.bulk') }}" class="hover:text-amber-400 transition font-semibold text-amber-300">Bulk Wholesale Portal</a></li>
                        <li><a href="{{ route('account.orders') }}" class="hover:text-cyan-400 transition">Track Consignment</a></li>
                        <li><a href="{{ route('account.addresses') }}" class="hover:text-cyan-400 transition">Delivery Addresses</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-cyan-400 transition">Export Trade Inquiries</a></li>
                        <li><a href="{{ url('/admin') }}" target="_blank" class="hover:text-indigo-400 transition font-semibold text-indigo-300">Admin Control Panel</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200">Compliance & Trust</h3>
                    <ul class="mt-3 space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-cyan-400 transition">Privacy Policy</a></li>
                        <li><a href="{{ route('page.terms') }}" class="hover:text-cyan-400 transition">Terms of Sale & Trade</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-cyan-400 transition">CE & RoHS Compliance</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-cyan-400 transition">Warranty & Returns</a></li>
                    </ul>
                </div>

            </div>

            <div class="mt-12 border-t border-slate-800/80 pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} A&amp;C Electronics Ltd. Built with Laravel 11 &amp; Lunar PHP.</p>
                <div class="mt-4 md:mt-0 flex items-center space-x-3">
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-[11px] font-semibold text-slate-400">Cash on Delivery</span>
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-[11px] font-semibold text-slate-400">Razorpay Online</span>
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-[11px] font-semibold text-slate-400">Bank Wire Transfer</span>
                    <span class="rounded bg-slate-900 border border-slate-800 px-2 py-1 text-[11px] font-semibold text-slate-400">Visa / Mastercard</span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts

    <script>
        function globalCartHandler() {
            return {
                cartCount: {{ $cartCount }},
                cartTotal: '{{ $cartTotalFormatted }}',
                toast: {
                    visible: false,
                    title: '',
                    timeout: null
                },
                onCartUpdated(detail) {
                    if (detail && detail.summary) {
                        this.cartCount = detail.summary.total_items;
                        this.cartTotal = detail.summary.total_formatted;
                    }
                    this.toast.title = detail.title || 'Product added to cart';
                    this.toast.visible = true;

                    clearTimeout(this.toast.timeout);
                    this.toast.timeout = setTimeout(() => {
                        this.toast.visible = false;
                    }, 4500);
                }
            }
        }

        // Global AJAX Add-To-Cart listener for any form with data-ajax-cart or inside product cards
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
                        <svg class="h-4 w-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Adding...</span>
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
                        // Product title from nearest card or PDP
                        const card = form.closest('.group') || form.closest('main') || document;
                        const productTitle = card.querySelector('h1, h2, h3')?.innerText?.trim() || 'Item';

                        window.dispatchEvent(new CustomEvent('cart-updated', {
                            detail: {
                                summary: data.summary,
                                title: productTitle
                            }
                        }));

                        if (submitBtn) {
                            submitBtn.innerHTML = `
                                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span>Added ✓</span>
                            `;
                            setTimeout(() => {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalHtml;
                            }, 1800);
                        }
                    } else {
                        alert(data.message || 'Could not add item to cart. Please check stock or quantity.');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalHtml;
                        }
                    }
                } catch (err) {
                    console.error('Add to cart AJAX error:', err);
                    form.submit(); // fallback to standard submit if network error
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
