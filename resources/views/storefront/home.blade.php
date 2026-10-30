@extends('layouts.app')

@section('title', 'Fresh Indian Groceries & Wholesale Merchandise')

@section('content')
<div class="space-y-12 pb-16">

    <!-- 1. Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-800 via-emerald-900 to-slate-950 text-white py-12 md:py-16">
        <div class="absolute inset-0 bg-[radial-gradient(#22c55e_1px,transparent_1px)] [background-size:16px_16px] opacity-10"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-500/20 border border-emerald-400/30 px-3.5 py-1 text-xs font-bold text-emerald-300">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Authentic Sourcing &bull; 15-Min Quick Commerce &bull; Wholesale Packs
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white">
                        Your Daily Fresh Mandi &amp; Wholesale Store in One Place
                    </h1>

                    <p class="text-sm sm:text-base text-emerald-100/90 max-w-xl leading-relaxed">
                        Handpicked Alphonso mangoes, organic unpolished pulses, 2-year aged Royal Basmati rice, and pure cow ghee. Delivered in 15 minutes or ordered in bulk cartons with up to 25% wholesale savings.
                    </p>

                    <!-- Interactive Pincode Checker Bar -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/15 max-w-lg shadow-xl" x-data="{
                        postcode: '{{ session('quick_postcode', '400001') }}',
                        statusMsg: '',
                        isChecking: false,
                        checkArea() {
                            if(!this.postcode) return;
                            this.isChecking = true;
                            fetch('{{ route('cart.check-serviceability') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ postcode: this.postcode })
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.isChecking = false;
                                this.statusMsg = data.message;
                            })
                            .catch(() => {
                                this.isChecking = false;
                                this.statusMsg = 'Service check unavailable right now.';
                            });
                        }
                    }">
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" x-model="postcode" placeholder="Enter Pincode (e.g. 400001, 110001, SW1A 1AA)..." class="w-full rounded-xl border-0 bg-white/95 px-3.5 py-2.5 text-xs font-bold text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-emerald-400">
                            </div>
                            <button type="button" @click="checkArea()" :disabled="isChecking" class="rounded-xl bg-amber-400 hover:bg-amber-500 px-4 py-2.5 text-xs font-extrabold text-slate-950 transition active:scale-95 shadow">
                                <span x-show="!isChecking">Check Express Delivery</span>
                                <span x-show="isChecking">Checking...</span>
                            </button>
                        </div>
                        <div x-show="statusMsg" class="mt-2 text-[11px] font-bold text-amber-300" x-text="statusMsg"></div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('experience.retail') }}" class="rounded-xl bg-emerald-500 hover:bg-emerald-600 px-5 py-3 text-xs font-extrabold text-white shadow-lg shadow-emerald-500/30 transition active:scale-95">
                            🛒 Shop Retail Groceries
                        </a>
                        <a href="{{ route('experience.bulk') }}" class="rounded-xl bg-amber-400 hover:bg-amber-500 px-5 py-3 text-xs font-extrabold text-slate-950 shadow-lg shadow-amber-400/20 transition active:scale-95">
                            📦 Wholesale Bulk Buy
                        </a>
                        <a href="{{ route('experience.quick') }}" class="rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 px-5 py-3 text-xs font-extrabold text-white transition">
                            ⚡ 15-Min Quick Shop
                        </a>
                    </div>
                </div>

                <!-- Right Hero Showcase Cards -->
                <div class="lg:col-span-5 grid grid-cols-2 gap-3.5">
                    <div class="rounded-2xl bg-white/10 backdrop-blur-md p-4 border border-white/15 space-y-2">
                        <div class="text-2xl">🥭</div>
                        <h4 class="text-xs font-extrabold text-white">Alphonso Mangoes</h4>
                        <p class="text-[11px] text-emerald-200">GI Certified Ratnagiri harvest, carbide-free.</p>
                        <span class="inline-block text-xs font-black text-amber-300">From €14.99 / Box</span>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur-md p-4 border border-white/15 space-y-2">
                        <div class="text-2xl">🌾</div>
                        <h4 class="text-xs font-extrabold text-white">Royal Basmati</h4>
                        <p class="text-[11px] text-emerald-200">2-Year aged super-long pearl grains.</p>
                        <span class="inline-block text-xs font-black text-amber-300">From €12.99 Bulk</span>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur-md p-4 border border-white/15 space-y-2">
                        <div class="text-2xl">🥛</div>
                        <h4 class="text-xs font-extrabold text-white">Pure Desi Ghee</h4>
                        <p class="text-[11px] text-emerald-200">Granular golden aroma, traditional standards.</p>
                        <span class="inline-block text-xs font-black text-amber-300">1 Litre Tin €11.99</span>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur-md p-4 border border-white/15 space-y-2">
                        <div class="text-2xl">⚡</div>
                        <h4 class="text-xs font-extrabold text-white">Hyperlocal Hubs</h4>
                        <p class="text-[11px] text-emerald-200">Chilled rapid dispatch in 15 minutes.</p>
                        <span class="inline-block text-xs font-black text-amber-300">7 Active Hubs</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Categories Navigation Grid -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Explore Categories</h2>
                <p class="text-xs text-slate-500">Carefully curated fresh staples, groceries and pantry items</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition flex items-center gap-1">
                <span>View All 24 Products</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3.5">
            @foreach ($collections as $collection)
                @php
                    $slug = $collection->urls->first()?->slug ?? 'category';
                    $icons = [
                        'fresh-produce' => '🥬',
                        'dairy-bakery' => '🧀',
                        'pantry-staples' => '🌾',
                        'spices-oils' => '🌶️',
                        'beverages' => '☕',
                        'snacks-sweets' => '🍪',
                    ];
                    $icon = $icons[$slug] ?? '🛒';
                @endphp
                <a href="{{ route('products.category', $slug) }}" class="group flex flex-col items-center justify-center rounded-2xl border border-slate-200/90 bg-white p-4 text-center shadow-sm hover:shadow-md hover:border-emerald-500 transition">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-2xl group-hover:scale-110 transition duration-200 shadow-inner">
                        {{ $icon }}
                    </div>
                    <h3 class="mt-3 text-xs font-bold text-slate-800 group-hover:text-emerald-600 transition leading-tight line-clamp-2">
                        {{ $collection->translateAttribute('name') }}
                    </h3>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 3. Quick Commerce 15-Minute Express Flash Items -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 p-6 sm:p-8 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-amber-400 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-slate-950 mb-2 shadow">
                        ⚡ 15-MINUTE EXPRESS DISPATCH
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white">Need Groceries in a Flash?</h2>
                    <p class="text-xs text-indigo-200">Chilled dairy, leafy greens, morning breakfast staples dispatched immediately.</p>
                </div>
                <a href="{{ route('experience.quick') }}" class="self-start md:self-auto rounded-xl bg-white px-4 py-2.5 text-xs font-black text-indigo-950 hover:bg-indigo-50 shadow transition active:scale-95">
                    Open Quick Shop Mode &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
                @foreach ($quickShopProducts as $qProduct)
                    <x-quick-product-card :product="$qProduct" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. Featured Daily Groceries (Retail) -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Daily Grocery Essentials</h2>
                <p class="text-xs text-slate-500">Retail single items, pure ingredients, certified quality</p>
            </div>
            <a href="{{ route('experience.retail') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">
                Explore Full Retail Store &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- 5. Wholesale Bulk Buy Deals (Volume Savings) -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border-2 border-amber-300 bg-gradient-to-b from-amber-50/70 to-white p-6 sm:p-8 shadow-md">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-2.5 py-0.5 text-[10px] font-black uppercase text-slate-950 mb-2 shadow-sm">
                        📦 WHOLESALE &amp; BULK SAVERS
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Buy in Bulk, Save Big</h2>
                    <p class="text-xs text-slate-600">Master cartons, 5kg sacks, and wholesale packaging with automatic tiered discounts.</p>
                </div>
                <a href="{{ route('experience.bulk') }}" class="self-start md:self-auto rounded-xl bg-amber-500 hover:bg-amber-600 px-4 py-2.5 text-xs font-black text-slate-950 transition shadow active:scale-95">
                    Browse All Bulk Deals &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($bulkBuyProducts as $bulkProduct)
                    <x-bulk-product-card :product="$bulkProduct" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. Serviceable Delivery Areas Section -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Active Express Delivery Hubs &amp; Coverage
                </h3>
                <span class="text-xs text-slate-400">All standard postal codes supported nationwide</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 text-xs">
                @foreach ($deliveryAreas as $area)
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                        <div class="flex items-center justify-between font-bold text-slate-800">
                            <span>{{ $area->city }}</span>
                            <span class="rounded bg-emerald-100 text-emerald-800 px-1.5 py-0.5 text-[10px] font-extrabold">{{ $area->delivery_time_minutes }}m</span>
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1">{{ $area->name }}</div>
                        <div class="text-[10px] font-mono font-bold text-slate-400 mt-0.5">Postcode: {{ $area->postcode }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</div>
@endsection

