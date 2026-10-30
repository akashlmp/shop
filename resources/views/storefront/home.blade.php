@extends('layouts.app')

@section('title', 'A&C Electronics - Connecting Markets, Delivering Excellence')

@section('content')
<div class="space-y-14 pb-20">

    <!-- 1. Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white py-14 md:py-20 border-b border-slate-800">
        <div class="absolute inset-0 bg-[radial-gradient(#4f46e5_1px,transparent_1px)] [background-size:24px_24px] opacity-15"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full bg-indigo-500/15 border border-indigo-500/30 px-4 py-1 text-xs font-bold text-indigo-300">
                        <span class="flex h-2 w-2 rounded-full bg-cyan-400 animate-ping"></span>
                        Authorized Global Electronics Trading &bull; B2B &amp; Retail
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white">
                        Global Electronics Trading. <br class="hidden sm:inline">
                        <span class="bg-gradient-to-r from-indigo-400 via-cyan-400 to-indigo-300 bg-clip-text text-transparent">
                            Delivering Excellence.
                        </span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-300 max-w-xl leading-relaxed">
                        Direct-from-factory distribution of Lenovo and AOC acoustic hardware, Active Noise Cancelling headphones, AMOLED smartwatches, and computer peripherals. Available for single unit retail or master carton export containers.
                    </p>

                    <!-- Trade Highlights / Quick Stats -->
                    <div class="grid grid-cols-3 gap-3 max-w-lg pt-1">
                        <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-3 text-center">
                            <span class="block text-xl font-black text-cyan-400">30+</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Models in Stock</span>
                        </div>
                        <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-3 text-center">
                            <span class="block text-xl font-black text-indigo-400">Up to 40%</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Carton Savings</span>
                        </div>
                        <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-3 text-center">
                            <span class="block text-xl font-black text-emerald-400">100%</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">CE &amp; RoHS Tested</span>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-3 pt-3">
                        <a href="{{ route('products.index') }}" class="rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 px-6 py-3.5 text-xs font-black text-white shadow-xl shadow-indigo-600/30 transition active:scale-95 flex items-center gap-2">
                            <span>Browse All Electronics</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('experience.bulk') }}" class="rounded-xl bg-amber-400 hover:bg-amber-300 px-6 py-3.5 text-xs font-black text-slate-950 shadow-xl shadow-amber-400/20 transition active:scale-95 flex items-center gap-2">
                            <span>📦 Bulk Wholesale Portal</span>
                        </a>
                        <a href="{{ route('experience.retail') }}" class="rounded-xl bg-slate-800/90 hover:bg-slate-800 border border-slate-700 px-6 py-3.5 text-xs font-bold text-slate-200 transition">
                            Retail Catalog
                        </a>
                    </div>
                </div>

                <!-- Right Hero Feature Cards -->
                <div class="lg:col-span-5 grid grid-cols-2 gap-3.5">
                    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md p-4 border border-slate-800 space-y-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-400 font-bold">🎧</div>
                        <h4 class="text-xs font-extrabold text-white">Lenovo EA Series</h4>
                        <p class="text-[11px] text-slate-400">Bluetooth 5.3 &amp; 5.4 Jerry cores, composite diaphragms.</p>
                        <span class="inline-block text-xs font-black text-cyan-400">Retail from €5.99</span>
                    </div>

                    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md p-4 border border-slate-800 space-y-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyan-500/20 text-cyan-400 font-bold">🔇</div>
                        <h4 class="text-xs font-extrabold text-white">AOC Hybrid ANC</h4>
                        <p class="text-[11px] text-slate-400">-40dB Active Noise Cancellation &amp; Realtek DSP.</p>
                        <span class="inline-block text-xs font-black text-cyan-400">Retail from €14.99</span>
                    </div>

                    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md p-4 border border-slate-800 space-y-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/20 text-amber-400 font-bold">⌚</div>
                        <h4 class="text-xs font-extrabold text-white">Smartwatches</h4>
                        <p class="text-[11px] text-slate-400">1.96" Borderless screens, Bluetooth calls, SpO2 &amp; IP68.</p>
                        <span class="inline-block text-xs font-black text-amber-400">Retail from €18.99</span>
                    </div>

                    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md p-4 border border-slate-800 space-y-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-400 font-bold">⚖️</div>
                        <h4 class="text-xs font-extrabold text-white">Diagnostic Scales</h4>
                        <p class="text-[11px] text-slate-400">Full-surface ITO glass, Fitdays app sync &amp; VA color LCD.</p>
                        <span class="inline-block text-xs font-black text-emerald-400">Retail from €9.99</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Categories Section -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">Electronics Categories</h2>
                <p class="text-xs text-slate-500 mt-1">Explore our 7 certified hardware and consumer electronic departments</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                View All Categories &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 sm:gap-4">
            @foreach ($collections as $collection)
                @php
                    $catUrl = $collection->urls->first()?->slug ?? $collection->id;
                    $catName = $collection->translateAttribute('name');
                @endphp
                <a href="{{ route('products.category', $catUrl) }}" class="group flex flex-col items-center text-center p-3.5 rounded-2xl border border-slate-200/80 bg-white hover:border-indigo-500 hover:shadow-lg transition duration-200">
                    <div class="h-16 w-16 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden mb-2.5 group-hover:scale-105 transition">
                        @if ($collection->slug === 'tws-earbuds')
                            <span class="text-2xl">🎧</span>
                        @elseif ($collection->slug === 'anc-audio')
                            <span class="text-2xl">🔇</span>
                        @elseif ($collection->slug === 'open-ear-sports')
                            <span class="text-2xl">🏃</span>
                        @elseif ($collection->slug === 'gaming-over-ear')
                            <span class="text-2xl">🎮</span>
                        @elseif ($collection->slug === 'smartwatches-wearables')
                            <span class="text-2xl">⌚</span>
                        @elseif ($collection->slug === 'computer-mice')
                            <span class="text-2xl">🖱️</span>
                        @elseif ($collection->slug === 'smart-scales')
                            <span class="text-2xl">⚖️</span>
                        @else
                            <span class="text-2xl">⚡</span>
                        @endif
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition leading-tight line-clamp-2">
                        {{ $catName }}
                    </h3>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 3. Featured Electronic Products Grid -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">Featured Releases</h2>
                    <span class="rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-black px-2 py-0.5 uppercase tracking-wide">Lenovo &amp; AOC</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">High-demand acoustic devices and peripherals with dynamic cart adding</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                View All Products &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- 4. High-Performance Audio Spotlight (TWS & Gaming) -->
    <section class="bg-slate-900 py-12 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-cyan-400">Wireless Acoustic Engineering</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-white mt-1">TWS Earbuds &amp; Low-Latency Gaming</h2>
                    <p class="text-xs text-slate-400 mt-1">Equipped with Jerry &amp; Bluetrum low-latency chips and composite dynamic horns</p>
                </div>
                <a href="{{ route('products.category', 'tws-earbuds') }}" class="rounded-xl border border-slate-700 bg-slate-800/80 px-4 py-2 text-xs font-bold text-slate-200 hover:bg-slate-700 transition">
                    Explore All Audio &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 text-slate-900">
                @foreach ($twsProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. B2B Wholesale / Master Carton Section -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-orange-50 p-6 sm:p-10 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="max-w-2xl space-y-4">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-amber-400/20 border border-amber-400/30 px-3 py-1 text-xs font-extrabold text-amber-800">
                        📦 Commercial Exporters &amp; B2B Distributors
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                        Save Up to 40% with Master Carton Volume Tiers
                    </h2>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-600">
                        Whether sourcing 5 units for your store or full master cartons (50–100 pcs) for international resale, A&amp;C Electronics provides transparent pricing breaks, gross weight specs, and palletized commercial air cargo dispatch.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('experience.bulk') }}" class="rounded-xl bg-amber-500 hover:bg-amber-600 px-6 py-3 text-xs font-black text-slate-950 shadow-md shadow-amber-500/20 transition active:scale-95">
                            Enter Bulk Wholesale Portal &rarr;
                        </a>
                        <a href="{{ route('page.contact') }}" class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                            Request Container Quote
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:w-96">
                    <div class="rounded-2xl border border-amber-200/80 bg-white p-4 shadow-sm">
                        <span class="text-xs font-bold text-slate-400">Minimum Order</span>
                        <h4 class="text-xl font-black text-slate-900 mt-1">5 Units</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Low barrier for testing new models</p>
                    </div>
                    <div class="rounded-2xl border border-amber-200/80 bg-white p-4 shadow-sm">
                        <span class="text-xs font-bold text-slate-400">Master Carton</span>
                        <h4 class="text-xl font-black text-amber-600 mt-1">50-100 Pcs</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Factory carton export packaging</p>
                    </div>
                    <div class="rounded-2xl border border-amber-200/80 bg-white p-4 shadow-sm">
                        <span class="text-xs font-bold text-slate-400">International Cargo</span>
                        <h4 class="text-xl font-black text-indigo-600 mt-1">Air &amp; Pallet</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Free freight over €250+</p>
                    </div>
                    <div class="rounded-2xl border border-amber-200/80 bg-white p-4 shadow-sm">
                        <span class="text-xs font-bold text-slate-400">Compliance</span>
                        <h4 class="text-xl font-black text-emerald-600 mt-1">CE / RoHS</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Full documentation provided</p>
                    </div>
                </div>
            </div>

            <!-- Bulk Products Preview -->
            <div class="mt-8 pt-8 border-t border-amber-200/70">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-4">Popular Wholesale Models</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-4">
                    @foreach ($bulkBuyProducts->take(3) as $p)
                        <x-product-card :product="$p" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
