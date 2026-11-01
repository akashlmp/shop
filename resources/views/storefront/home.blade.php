@extends('layouts.app')

@section('title', 'A&C Electronics - Connecting Global Brands to UK Markets')
@section('meta_description', 'A&C Electronics is your premier gateway to global electronic brands in the UK. Direct OEM sourcing for Lenovo & AOC audio, smart wearables, and computer peripherals.')

@section('content')
<div class="space-y-16 sm:space-y-24 pb-16">

    <!-- 1. High-Tech Enterprise Hero Section -->
    <section class="relative overflow-hidden bg-slate-950 text-white py-16 sm:py-24 lg:py-28 border-b border-slate-800">
        <!-- Ambient Grid Background & Subtle Radial Glow -->
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:28px_28px]"></div>
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-blue-600/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-cyan-600/20 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-950/80 border border-blue-500/30 px-4 py-1 text-xs font-semibold tracking-wide text-blue-400">
                        <span class="inline-block h-2 w-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Powering Global Trade, Building Trust</span>
                        <span class="text-slate-600">&bull;</span>
                        <span class="text-slate-300">UK Distribution</span>
                    </div>

                    <h1 class="font-display text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-tight">
                        Connecting Global Brands <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200">
                            to UK Markets
                        </span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto lg:mx-0 leading-relaxed font-light">
                        <strong>A&amp;C Electronics</strong> is your gateway to global markets. We provide direct OEM factory sourcing for Lenovo acoustic systems, AOC gaming audio, smart wearables, and computer peripherals with master carton wholesale pricing and fast UK dispatch.
                    </p>

                    <!-- Trust Stats -->
                    <div class="grid grid-cols-3 gap-3 max-w-md mx-auto lg:mx-0 pt-2">
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-3.5 text-center">
                            <span class="block font-display text-xl sm:text-2xl font-black text-blue-400">100%</span>
                            <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Direct OEM</span>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-3.5 text-center">
                            <span class="block font-display text-xl sm:text-2xl font-black text-emerald-400">Up to 40%</span>
                            <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Carton Savings</span>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-3.5 text-center">
                            <span class="block font-display text-xl sm:text-2xl font-black text-white">24/48h</span>
                            <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">UK Tracked</span>
                        </div>
                    </div>

                    <!-- Dual Call to Action -->
                    <div class="flex flex-wrap justify-center lg:justify-start gap-3.5 pt-4">
                        <a href="{{ route('products.index') }}"
                           class="rounded-full bg-blue-600 px-8 py-3.5 text-xs font-bold uppercase tracking-wider text-white hover:bg-blue-500 shadow-xl transition active:scale-95">
                            Browse Electronics &rarr;
                        </a>
                        <a href="{{ route('experience.bulk') }}"
                           class="rounded-full border border-slate-700 bg-slate-900/80 px-7 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-200 hover:bg-slate-800 hover:text-white transition active:scale-95 flex items-center gap-2">
                            <span>B2B Master Carton Portal</span>
                            <span class="text-[10px] bg-blue-500/20 text-blue-400 border border-blue-500/30 px-1.5 py-0.5 rounded">Wholesale</span>
                        </a>
                    </div>
                </div>

                <!-- Right Hero Electronics Showcase Cards -->
                <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div class="relative overflow-hidden rounded-2xl bg-slate-900 aspect-[4/5] shadow-2xl border border-slate-800 group">
                            <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=700&q=80"
                                 alt="Lenovo Wireless TWS Earbuds"
                                 class="h-full w-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent flex flex-col justify-end p-4">
                                <span class="text-[9px] uppercase tracking-wider text-blue-400 font-bold">Lenovo EA Series</span>
                                <span class="text-xs font-display font-bold text-white">TWS Wireless Earbuds</span>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-slate-900/90 p-4 border border-slate-800 text-center">
                            <p class="text-[11px] text-blue-400 font-semibold">⚡ Bluetooth 5.4 &bull; Low Latency</p>
                        </div>
                    </div>

                    <div class="space-y-4 pt-6">
                        <div class="rounded-2xl bg-slate-900/90 p-4 border border-slate-800 text-center">
                            <p class="text-[11px] text-emerald-400 font-semibold">🛡️ CE &amp; RoHS Certified</p>
                        </div>
                        <div class="relative overflow-hidden rounded-2xl bg-slate-900 aspect-[4/5] shadow-2xl border border-slate-800 group">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=700&q=80"
                                 alt="AOC Hybrid ANC Headset"
                                 class="h-full w-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent flex flex-col justify-end p-4">
                                <span class="text-[9px] uppercase tracking-wider text-cyan-400 font-bold">AOC Acoustic</span>
                                <span class="text-xs font-display font-bold text-white">Hybrid -40dB ANC</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Four Trade Pillars Strip -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-2xs flex flex-col items-center text-center tech-card-hover">
                <span class="text-2xl mb-2">🌐</span>
                <h3 class="font-display text-sm font-bold text-slate-900">Direct OEM Sourcing</h3>
                <p class="mt-1 text-xs text-slate-500">Certified Lenovo &amp; AOC hardware direct from licensed factories.</p>
            </div>
            <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-2xs flex flex-col items-center text-center tech-card-hover">
                <span class="text-2xl mb-2">📦</span>
                <h3 class="font-display text-sm font-bold text-slate-900">Master Carton Wholesale</h3>
                <p class="mt-1 text-xs text-slate-500">Tiered volume discounts (5-50+ units) for UK trade resellers.</p>
            </div>
            <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-2xs flex flex-col items-center text-center tech-card-hover">
                <span class="text-2xl mb-2">✈️</span>
                <h3 class="font-display text-sm font-bold text-slate-900">Tracked UK &amp; Global Logistics</h3>
                <p class="mt-1 text-xs text-slate-500">Express air courier and pallet consignments with live tracking.</p>
            </div>
            <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-2xs flex flex-col items-center text-center tech-card-hover">
                <span class="text-2xl mb-2">🛡️</span>
                <h3 class="font-display text-sm font-bold text-slate-900">CE &amp; RoHS Certified</h3>
                <p class="mt-1 text-xs text-slate-500">Full UKCA compliance, 1-year warranty, and tested safety standards.</p>
            </div>
        </div>
    </section>

    <!-- 3. Curated Electronics Collections (Visual Tiles) -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-600">Hardware Categories</span>
            <h2 class="font-display text-2xl sm:text-4xl font-extrabold text-slate-900 mt-1">Explore Electronic Hardware</h2>
            <p class="mt-2 text-xs sm:text-sm text-slate-500">Certified consumer tech audio, smart health wearables, and computer peripherals.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- Category 1: TWS Earbuds -->
            <a href="{{ route('products.index', ['category' => 'tws-earbuds']) }}" class="group relative overflow-hidden rounded-2xl bg-slate-900 aspect-[4/5] shadow-xs tech-card-hover">
                <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80"
                     alt="TWS Earbuds & True Wireless"
                     class="h-full w-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex flex-col justify-end p-4 sm:p-5">
                    <span class="text-[10px] uppercase tracking-widest text-blue-400 font-bold">Bluetooth 5.4</span>
                    <h3 class="font-display text-base sm:text-lg font-bold text-white group-hover:text-blue-300 transition">TWS Earbuds</h3>
                    <span class="text-[11px] text-slate-300 mt-0.5">Explore Lenovo EA &rarr;</span>
                </div>
            </a>

            <!-- Category 2: ANC Audio -->
            <a href="{{ route('products.index', ['category' => 'anc-audio']) }}" class="group relative overflow-hidden rounded-2xl bg-slate-900 aspect-[4/5] shadow-xs tech-card-hover">
                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80"
                     alt="Active Noise Cancelling (ANC)"
                     class="h-full w-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex flex-col justify-end p-4 sm:p-5">
                    <span class="text-[10px] uppercase tracking-widest text-blue-400 font-bold">-40dB Hybrid ANC</span>
                    <h3 class="font-display text-base sm:text-lg font-bold text-white group-hover:text-blue-300 transition">ANC Audio</h3>
                    <span class="text-[11px] text-slate-300 mt-0.5">AOC Gaming &amp; Travel &rarr;</span>
                </div>
            </a>

            <!-- Category 3: Smartwatches -->
            <a href="{{ route('products.index', ['category' => 'smartwatches-wearables']) }}" class="group relative overflow-hidden rounded-2xl bg-slate-900 aspect-[4/5] shadow-xs tech-card-hover">
                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80"
                     alt="Smartwatches & Fitness Wearables"
                     class="h-full w-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex flex-col justify-end p-4 sm:p-5">
                    <span class="text-[10px] uppercase tracking-widest text-blue-400 font-bold">AMOLED &bull; IP68</span>
                    <h3 class="font-display text-base sm:text-lg font-bold text-white group-hover:text-blue-300 transition">Smartwatches</h3>
                    <span class="text-[11px] text-slate-300 mt-0.5">Biometric Fitness &rarr;</span>
                </div>
            </a>

            <!-- Category 4: Computer Mice & Peripherals -->
            <a href="{{ route('products.index', ['category' => 'computer-mice']) }}" class="group relative overflow-hidden rounded-2xl bg-slate-900 aspect-[4/5] shadow-xs tech-card-hover">
                <img src="https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=600&q=80"
                     alt="Computer Mice & Peripherals"
                     class="h-full w-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex flex-col justify-end p-4 sm:p-5">
                    <span class="text-[10px] uppercase tracking-widest text-blue-400 font-bold">Tri-Mode Wireless</span>
                    <h3 class="font-display text-base sm:text-lg font-bold text-white group-hover:text-blue-300 transition">Peripherals</h3>
                    <span class="text-[11px] text-slate-300 mt-0.5">Ergonomic Mice &rarr;</span>
                </div>
            </a>

        </div>
    </section>

    <!-- 4. Bestsellers Hardware Showcase (2-Column Mobile, 4-Column Desktop) -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8 pb-4 border-b border-slate-200">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-600">Featured Releases</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Bestselling Hardware</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-wider text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                <span>View Full Catalog</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-4 text-center py-12 bg-white rounded-2xl border border-slate-200">
                    <p class="text-slate-500 text-sm">No electronics listed at the moment.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 5. Retail vs Wholesale Split Feature Section -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl bg-slate-900 text-white border border-slate-800 shadow-xl">
            <div class="grid grid-cols-1 lg:grid-cols-12 items-center">
                
                <!-- Left Lifestyle Graphic -->
                <div class="lg:col-span-6 relative aspect-video lg:aspect-auto lg:h-full min-h-[340px]">
                    <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=900&q=80"
                         alt="A&C Electronics International Logistics"
                         class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-slate-950/40"></div>
                    <div class="absolute bottom-4 left-4 rounded-xl bg-slate-900/90 backdrop-blur-xs px-3.5 py-1.5 text-xs font-semibold text-white border border-slate-700 shadow">
                        📦 Master Carton Consignments &bull; London Logistics Hub
                    </div>
                </div>

                <!-- Right Copy & Value Propositions -->
                <div class="lg:col-span-6 p-8 sm:p-12 space-y-6">
                    <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-400">Global Trade. Trusted Partnerships.</span>
                    <h2 class="font-display text-2xl sm:text-4xl font-extrabold text-white leading-tight">
                        Connecting Markets, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">
                            Delivering Excellence
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-light">
                        Whether purchasing single retail hardware units or reserving full master carton pallet consignments, <strong>A&amp;C Electronics</strong> ensures verified factory quality, rapid customs clearance, and predictable trade fulfillment.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="rounded-xl bg-slate-800/80 p-4 border border-slate-700 space-y-1.5">
                            <h4 class="text-xs font-bold text-blue-400 uppercase tracking-wider">Retail Consumers</h4>
                            <p class="text-[11px] text-slate-300">Individual unit orders with next-day UK dispatch, complete accessories, and 12-month UK replacement warranty.</p>
                        </div>
                        <div class="rounded-xl bg-slate-800/80 p-4 border border-slate-700 space-y-1.5">
                            <h4 class="text-xs font-bold text-emerald-400 uppercase tracking-wider">B2B Wholesalers</h4>
                            <p class="text-[11px] text-slate-300">Tiered carton rates (5-50+ units), container volume logistics, commercial VAT invoices, and dedicated trade managers.</p>
                        </div>
                    </div>

                    <div class="pt-2 flex flex-wrap gap-3">
                        <a href="{{ route('experience.bulk') }}" class="inline-block rounded-full bg-blue-600 px-7 py-3 text-xs font-bold uppercase tracking-wider text-white hover:bg-blue-500 shadow-md transition">
                            Explore Master Carton Tiers &rarr;
                        </a>
                        <a href="{{ route('page.contact') }}" class="inline-block rounded-full border border-slate-700 bg-slate-800 px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-200 hover:bg-slate-700 transition">
                            Inquire with Trade Desk
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 6. Secondary Hardware Collection (ANC & Sports Audio) -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8 pb-4 border-b border-slate-200">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-600">Acoustic Engineering</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">High-Fidelity Audio &amp; Headsets</h2>
            </div>
            <a href="{{ route('products.index', ['category' => 'anc-audio']) }}" class="text-xs font-bold uppercase tracking-wider text-blue-600 hover:text-blue-800 transition">
                Browse Audio &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @php
                $secondaryList = $twsProducts->isNotEmpty() ? $twsProducts : $featuredProducts->slice(0, 4);
            @endphp
            @forelse ($secondaryList as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-4 text-center py-8 text-xs text-slate-400">
                    No hardware listed in this collection.
                </div>
            @endforelse
        </div>
    </section>

    <!-- 7. Verified Partner & Buyer Reviews -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-white border border-slate-200 p-8 sm:p-12 shadow-xs">
            <div class="text-center max-w-xl mx-auto mb-10">
                <div class="flex justify-center items-center gap-1 text-amber-500 text-base mb-2">
                    ★★★★★
                </div>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900">Trusted by UK Trade Partners</h2>
                <p class="mt-2 text-xs text-slate-500">Read what retail store managers, wholesale resellers, and tech buyers say about A&amp;C Electronics.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Review 1 -->
                <div class="rounded-2xl bg-slate-50 p-6 border border-slate-200/80 space-y-3">
                    <div class="flex text-amber-500 text-xs">★★★★★</div>
                    <p class="text-xs text-slate-700 leading-relaxed">
                        "We ordered 50 master cartons of the Lenovo EA Series earbuds for our high street electronics outlets in Birmingham. The packaging was pristine, CE certificates were included in the bill of lading, and margin savings were substantial."
                    </p>
                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-900">Marcus Sterling</span>
                        <span class="text-[10px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-full">Retail Store Director</span>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="rounded-2xl bg-slate-50 p-6 border border-slate-200/80 space-y-3">
                    <div class="flex text-amber-500 text-xs">★★★★★</div>
                    <p class="text-xs text-slate-700 leading-relaxed">
                        "The AOC Hybrid ANC headphones are phenomenal sellers for gaming and hybrid work setups. A&amp;C Electronics provided instant tracking and delivered our pallet within 48 hours. A truly reliable UK trade partner."
                    </p>
                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-900">Julian Price</span>
                        <span class="text-[10px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-full">Commercial Procurement</span>
                    </div>
                </div>

                <!-- Review 3 -->
                <div class="rounded-2xl bg-slate-50 p-6 border border-slate-200/80 space-y-3">
                    <div class="flex text-amber-500 text-xs">★★★★★</div>
                    <p class="text-xs text-slate-700 leading-relaxed">
                        "Ordered a single unit Lenovo wireless system to test quality before placing a bulk corporate gift order. The audio clarity and battery life exceeded expectations. Great customer service at the trade desk."
                    </p>
                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-900">Emma Thornton</span>
                        <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">Verified Tech Buyer</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Trade Desk Newsletter & Corporate Inquiries -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-slate-950 text-white p-8 sm:p-14 border border-slate-800 text-center max-w-4xl mx-auto space-y-5 shadow-2xl">
            <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-400">Trade Intelligence &amp; Inquiries</span>
            <h2 class="font-display text-2xl sm:text-4xl font-extrabold">Your Gateway to Global Markets</h2>
            <p class="text-xs sm:text-sm text-slate-400 max-w-lg mx-auto leading-relaxed font-light">
                Subscribe for factory inventory alerts, new Lenovo &amp; AOC arrivals, and master carton allocation schedules. Use code <strong class="text-blue-400 font-mono">B2B50</strong> for wholesale shipping credits.
            </p>
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Trade desk subscription confirmed! Use trade coupon code B2B50 at checkout.');" class="max-w-md mx-auto flex flex-col sm:flex-row gap-2 pt-2">
                <input type="email" required placeholder="Enter corporate email address" class="flex-1 rounded-full border border-slate-700 bg-slate-900 px-5 py-3 text-xs text-white placeholder-slate-500 focus:border-blue-500 focus:outline-none">
                <button type="submit" class="rounded-full bg-blue-600 hover:bg-blue-500 px-7 py-3 text-xs font-bold uppercase tracking-wider text-white transition">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

</div>
@endsection
