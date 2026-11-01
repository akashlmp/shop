@extends('layouts.app')

@php
    $productName = $product->translateAttribute('name') ?? 'Electronic Device';
    $brand = 'A&C Certified';
    if (stripos($productName, 'Lenovo') !== false) {
        $brand = 'Lenovo OEM';
    } elseif (stripos($productName, 'AOC') !== false) {
        $brand = 'AOC Gaming';
    }
@endphp

@section('title', $productName . ' - A&C Electronics')
@section('meta_description', 'Buy ' . $productName . ' at A&C Electronics. Direct OEM sourcing, tiered wholesale volume pricing, CE/RoHS certified, and fast UK dispatch.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-16"
     x-data="{
        qty: 1,
        activeTab: 'desc',
        stickyBarVisible: false,
        init() {
            window.addEventListener('scroll', () => {
                const addBtn = document.getElementById('main-add-to-cart-btn');
                if (addBtn) {
                    const rect = addBtn.getBoundingClientRect();
                    this.stickyBarVisible = rect.bottom < 0;
                }
            });
        }
     }">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-blue-600 transition">Hardware Catalog</a>
        @if ($primaryColl = $product->collections->first())
            <span>/</span>
            <a href="{{ route('products.category', $primaryColl->urls->first()?->slug ?? '') }}" class="hover:text-blue-600 transition">
                {{ $primaryColl->translateAttribute('name') }}
            </a>
        @endif
        <span>/</span>
        <span class="text-slate-900 font-semibold line-clamp-1">{{ $productName }}</span>
    </nav>

    <!-- Main Showcase & Purchase Column -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
        
        <!-- Left: Product Image Gallery -->
        <div class="lg:col-span-6 space-y-4">
            @php
                $primaryImage = $metadata?->image_url ?? $product->primary_image_url ?? 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=900&q=80';
            @endphp
            
            <div class="relative overflow-hidden rounded-3xl bg-slate-100 aspect-[4/5] shadow-xs border border-slate-200 group">
                <img src="{{ $primaryImage }}"
                     alt="{{ $productName }}"
                     class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105">
                
                <div class="absolute top-4 left-4 flex flex-col gap-1.5 items-start">
                    <span class="rounded-lg bg-slate-950/85 backdrop-blur-xs px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-2xs border border-white/10">
                        {{ $brand }}
                    </span>
                    <span class="rounded-lg bg-blue-600 text-white px-3 py-1 text-[10px] font-bold shadow-2xs">
                        Direct OEM Sourcing
                    </span>
                </div>
            </div>

            <!-- Guarantee Badges Strip -->
            <div class="grid grid-cols-3 gap-3 text-center text-xs text-slate-700">
                <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-2xs">
                    <span class="block text-base mb-1">⚡</span>
                    <span class="font-bold block text-[11px] text-slate-900">Direct OEM</span>
                    <span class="text-[9px] text-slate-500">100% Genuine</span>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-2xs">
                    <span class="block text-base mb-1">📦</span>
                    <span class="font-bold block text-[11px] text-slate-900">Carton Wholesale</span>
                    <span class="text-[9px] text-slate-500">Tiered Pricing</span>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-2xs">
                    <span class="block text-base mb-1">🛡️</span>
                    <span class="font-bold block text-[11px] text-slate-900">1-Yr Warranty</span>
                    <span class="text-[9px] text-slate-500">CE &amp; RoHS Tested</span>
                </div>
            </div>
        </div>

        <!-- Right: Purchasing Controls & Volume Pricing Matrix -->
        <div class="lg:col-span-6 space-y-6">
            
            <div>
                <!-- Collection / Brand Subhead -->
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-600 mb-1.5">
                    <span>{{ $product->collections->first()?->translateAttribute('name') ?? 'Consumer Electronics' }}</span>
                    <span>&bull;</span>
                    <span>UK Logistics Hub</span>
                </div>

                <!-- Product Title -->
                <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-950 leading-tight">
                    {{ $productName }}
                </h1>

                <!-- Reviews Badge -->
                <div class="mt-2.5 flex items-center gap-2 text-xs">
                    <div class="flex text-amber-500">★★★★★</div>
                    <span class="font-bold text-slate-800">4.9 / 5.0</span>
                    <span class="text-slate-400">&bull;</span>
                    <span class="text-slate-500 underline cursor-pointer">CE &amp; RoHS Certified Batch</span>
                </div>

                <!-- Pricing -->
                @php
                    $basePriceVal = $basePrice ? $basePrice->price->value : 0;
                    $comparePriceVal = $basePrice && $basePrice->compare_price ? $basePrice->compare_price->value : null;
                    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock > 0);
                    $tier1 = round($basePriceVal * 0.88);
                    $tier2 = round($basePriceVal * 0.75);
                @endphp
                <div class="mt-4 flex items-baseline gap-3">
                    <span class="font-display text-3xl font-black text-slate-950">
                        £{{ number_format($basePriceVal / 100, 2) }}
                    </span>
                    @if ($comparePriceVal)
                        <span class="text-base text-slate-400 line-through">
                            £{{ number_format($comparePriceVal / 100, 2) }}
                        </span>
                        <span class="rounded-md bg-blue-100 text-blue-800 px-2.5 py-0.5 text-xs font-bold">
                            Save £{{ number_format(($comparePriceVal - $basePriceVal) / 100, 2) }}
                        </span>
                    @endif
                </div>

                <p class="mt-1 text-[11px] text-slate-500">Retail price includes VAT. Tracked UK delivery with pallet courier options.</p>
            </div>

            <!-- Stock Availability -->
            <div class="flex items-center gap-2 text-xs">
                @if ($inStock)
                    <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-semibold text-emerald-900">
                        In stock &bull; Dispatched in 24-48 hours via Tracked Courier
                    </span>
                @else
                    <span class="flex h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                    <span class="font-semibold text-rose-800">Allocated to Incoming Consignment</span>
                @endif
            </div>

            <!-- Master Carton Tiered Volume Pricing Table -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 space-y-2.5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="text-base">📦</span>
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">B2B Master Carton Pricing</h3>
                            <p class="text-[10px] text-slate-500">Automatic volume discount applied at checkout</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md">Save up to 25%</span>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="rounded-xl border border-slate-200 bg-white p-2">
                        <span class="text-[10px] text-slate-500 block">1 - 4 Units</span>
                        <span class="font-bold text-slate-900">£{{ number_format($basePriceVal / 100, 2) }}</span>
                    </div>
                    <div class="rounded-xl border border-blue-200 bg-blue-50/60 p-2">
                        <span class="text-[10px] text-blue-700 font-semibold block">5 - 19 Units</span>
                        <span class="font-bold text-blue-900">£{{ number_format($tier1 / 100, 2) }}</span>
                    </div>
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-2">
                        <span class="text-[10px] text-emerald-700 font-semibold block">20+ Master Carton</span>
                        <span class="font-bold text-emerald-900">£{{ number_format($tier2 / 100, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Add to Bag Form -->
            @if ($variant && $inStock)
                <form action="{{ route('cart.add') }}" method="POST" data-ajax-cart="true" class="space-y-4">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">

                    <div class="flex items-center gap-3">
                        <!-- Quantity Controller -->
                        <div class="flex items-center rounded-full border border-slate-300 bg-white p-1">
                            <button type="button" @click="if(qty > 1) qty--" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-700 font-bold hover:bg-slate-100 transition">
                                -
                            </button>
                            <input type="number" name="quantity" x-model.number="qty" min="1" max="{{ $variant->stock }}" class="w-12 text-center border-0 bg-transparent text-xs font-bold text-slate-900 focus:ring-0">
                            <button type="button" @click="if(qty < {{ $variant->stock }}) qty++" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-700 font-bold hover:bg-slate-100 transition">
                                +
                            </button>
                        </div>

                        <!-- Add to Bag Button -->
                        <button type="submit"
                                id="main-add-to-cart-btn"
                                class="flex-1 rounded-full bg-blue-600 hover:bg-blue-500 py-3.5 px-6 text-xs font-bold uppercase tracking-wider text-white shadow-md transition active:scale-95 flex items-center justify-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25c-.67 0-1.19-.578-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span>Add to Bag</span>
                        </button>
                    </div>

                    <!-- Instant Checkout Button -->
                    <a href="{{ route('checkout.index') }}" class="block w-full rounded-full border border-slate-900 bg-white py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-900 hover:bg-slate-900 hover:text-white transition">
                        Instant Checkout &rarr;
                    </a>
                </form>
            @endif

            <!-- Collapsible Accordion Tabs (Dawn/Shopify style) -->
            <div class="border-t border-slate-200 divide-y divide-slate-200 text-xs">
                
                <!-- Tab 1: Description & Architecture -->
                <div class="py-3.5">
                    <button type="button" @click="activeTab = (activeTab === 'desc' ? '' : 'desc')" class="flex w-full items-center justify-between font-display text-sm font-bold text-slate-900">
                        <span>Technical Architecture &amp; Overview</span>
                        <span x-text="activeTab === 'desc' ? '−' : '+'" class="text-base text-slate-500 font-sans"></span>
                    </button>
                    <div x-show="activeTab === 'desc'" x-collapse class="pt-3 text-slate-600 leading-relaxed font-light space-y-2">
                        <p>{{ $product->translateAttribute('description') ?? 'Engineered with high-grade acoustic components, ultra-low latency chipsets, and certified battery cells. Optimized for crystal clear sound output, extended standby runtimes, and resilient daily operation.' }}</p>
                        <p>Backed by official UK warranty support and batch testing documentation.</p>
                    </div>
                </div>

                <!-- Tab 2: Hardware Specifications -->
                <div class="py-3.5">
                    <button type="button" @click="activeTab = (activeTab === 'specs' ? '' : 'specs')" class="flex w-full items-center justify-between font-display text-sm font-bold text-slate-900">
                        <span>Hardware Specifications</span>
                        <span x-text="activeTab === 'specs' ? '−' : '+'" class="text-base text-slate-500 font-sans"></span>
                    </button>
                    <div x-show="activeTab === 'specs'" x-collapse class="pt-3 space-y-2 text-slate-600">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <span class="text-slate-400">Brand / OEM:</span>
                            <span class="font-semibold text-slate-900">{{ $brand }}</span>
                            <span class="text-slate-400">Connectivity:</span>
                            <span class="font-semibold text-slate-900">Bluetooth 5.4 / Dual-Channel Low Latency</span>
                            <span class="text-slate-400">Certifications:</span>
                            <span class="font-semibold text-slate-900">CE, RoHS, UKCA, FCC</span>
                            <span class="text-slate-400">Logistics Packaging:</span>
                            <span class="font-semibold text-slate-900">Export Master Carton (50 units/carton)</span>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Master Carton Logistics -->
                <div class="py-3.5">
                    <button type="button" @click="activeTab = (activeTab === 'carton' ? '' : 'carton')" class="flex w-full items-center justify-between font-display text-sm font-bold text-slate-900">
                        <span>Master Carton &amp; Freight Specs</span>
                        <span x-text="activeTab === 'carton' ? '−' : '+'" class="text-base text-slate-500 font-sans"></span>
                    </button>
                    <div x-show="activeTab === 'carton'" x-collapse class="pt-3 text-slate-600 leading-relaxed font-light space-y-1.5">
                        <p><strong>Master Carton:</strong> 50 units per carton, double-wall corrugated export packaging with custom corner protection.</p>
                        <p><strong>Pallet Allocation:</strong> 24 cartons per standard EUR-pallet. Air cargo &amp; container consignments available.</p>
                    </div>
                </div>

                <!-- Tab 4: Warranty & Dispatch -->
                <div class="py-3.5">
                    <button type="button" @click="activeTab = (activeTab === 'ship' ? '' : 'ship')" class="flex w-full items-center justify-between font-display text-sm font-bold text-slate-900">
                        <span>Warranty &amp; UK Dispatch</span>
                        <span x-text="activeTab === 'ship' ? '−' : '+'" class="text-base text-slate-500 font-sans"></span>
                    </button>
                    <div x-show="activeTab === 'ship'" x-collapse class="pt-3 text-slate-600 leading-relaxed font-light space-y-1.5">
                        <p><strong>UK Delivery:</strong> Tracked express courier (24-48 hours). Pallet dispatch within 3 business days.</p>
                        <p><strong>Warranty:</strong> 12-Month UK replacement warranty on manufacturing defects with London RMA hub.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Mobile Sticky Purchase Bar -->
    <div x-cloak
         x-show="stickyBarVisible"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="md:hidden fixed bottom-14 inset-x-0 z-40 bg-white border-t border-slate-200 p-3 shadow-2xl flex items-center justify-between gap-3">
        <div class="min-w-0">
            <h4 class="text-xs font-bold text-slate-900 truncate">{{ $productName }}</h4>
            <span class="font-display text-sm font-black text-slate-950">£{{ number_format($basePriceVal / 100, 2) }}</span>
        </div>
        @if ($variant && $inStock)
            <form action="{{ route('cart.add') }}" method="POST" data-ajax-cart="true">
                @csrf
                <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="rounded-full bg-blue-600 px-5 py-2 text-xs font-bold text-white shadow uppercase tracking-wider">
                    Add to Bag
                </button>
            </form>
        @endif
    </div>

    <!-- Related Hardware Pieces -->
    @if ($relatedProducts && $relatedProducts->count() > 0)
        <section class="border-t border-slate-200 pt-12 space-y-6">
            <div class="flex items-end justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-600">You May Also Like</span>
                    <h2 class="font-display text-2xl font-bold text-slate-900 mt-1">Complementary Hardware</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                    View All &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($relatedProducts as $relProduct)
                    <x-product-card :product="$relProduct" />
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
