@extends('layouts.app')

@section('title', $product->translateAttribute('name') . ' - Nexora Global')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs font-semibold text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition">Electronics</a>
        @if ($primaryColl = $product->collections->first())
            <span>/</span>
            <a href="{{ route('products.category', $primaryColl->urls->first()?->slug ?? '') }}" class="hover:text-indigo-600 transition">
                {{ $primaryColl->translateAttribute('name') }}
            </a>
        @endif
        <span>/</span>
        <span class="text-slate-900 font-bold line-clamp-1">{{ $product->translateAttribute('name') }}</span>
    </nav>

    <!-- Product Showcase & Purchase -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start" x-data="{
        selectedColor: '{{ $metadata?->colors[0] ?? '' }}',
        qty: 1
    }">
        
        <!-- Left: Product Image & Compliance Badges -->
        <div class="lg:col-span-6 space-y-4">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-white aspect-square shadow-sm">
                <img src="{{ $metadata?->image_url ?? $product->primary_image_url }}"
                     alt="{{ $product->translateAttribute('name') }}"
                     class="h-full w-full object-cover object-center">
                
                <div class="absolute top-4 left-4 flex flex-col gap-2 items-start">
                    @if ($metadata?->brand)
                        <span class="rounded-lg bg-slate-950/90 backdrop-blur-md px-3 py-1 text-xs font-black tracking-wider text-white shadow border border-slate-700/50">
                            {{ strtoupper($metadata->brand) }}
                        </span>
                    @endif
                    @if ($metadata?->badge_text)
                        <span class="rounded-lg bg-indigo-600 px-3 py-1 text-xs font-black text-white shadow">
                            {{ $metadata->badge_text }}
                        </span>
                    @endif
                </div>

                @if ($metadata?->model_code)
                    <div class="absolute bottom-4 right-4 rounded-lg bg-slate-900/80 backdrop-blur-md px-2.5 py-1 text-xs font-mono font-bold text-cyan-300 border border-slate-700/60 shadow">
                        Model: {{ $metadata->model_code }}
                    </div>
                @endif
            </div>

            <!-- Global Trade Guarantees -->
            <div class="grid grid-cols-3 gap-3 text-center text-xs font-bold text-slate-700">
                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xs">
                    <span class="text-indigo-600 block text-base mb-1">🛡️</span>
                    CE &amp; RoHS Certified
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xs">
                    <span class="text-indigo-600 block text-base mb-1">✈️</span>
                    Tracked Air Cargo
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xs">
                    <span class="text-indigo-600 block text-base mb-1">📦</span>
                    Master Carton Export
                </div>
            </div>
        </div>

        <!-- Right: Purchasing Controls & Specs -->
        <div class="lg:col-span-6 space-y-6">
            
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                    <span>SKU: {{ $variant?->sku ?? 'N/A' }}</span>
                    <span>&bull;</span>
                    <span>Packaging: {{ $metadata?->bulk_package_type ?? 'Carton Pack' }}</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                    {{ $product->translateAttribute('name') }}
                </h1>

                <!-- Highlights Bar -->
                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    @if ($metadata?->chipset)
                        <span class="rounded-md bg-indigo-50 border border-indigo-200 px-2 py-0.5 font-bold text-indigo-700">
                            Core: {{ $metadata->chipset }}
                        </span>
                    @endif
                    @if ($metadata?->battery_specs)
                        <span class="rounded-md bg-cyan-50 border border-cyan-200 px-2 py-0.5 font-bold text-cyan-800">
                            Battery: {{ $metadata->battery_specs }}
                        </span>
                    @endif
                </div>

                <!-- Pricing Display -->
                @php
                    $basePriceVal = $basePrice ? $basePrice->price->value : 0;
                    $comparePriceVal = $basePrice && $basePrice->compare_price ? $basePrice->compare_price->value : null;
                    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock > 0);
                @endphp
                <div class="mt-4 flex items-baseline gap-3">
                    <span class="text-3xl font-black text-slate-950">
                        €{{ number_format($basePriceVal / 100, 2) }}
                    </span>
                    @if ($comparePriceVal)
                        <span class="text-lg text-slate-400 line-through">
                            €{{ number_format($comparePriceVal / 100, 2) }}
                        </span>
                        <span class="rounded-md bg-rose-100 text-rose-700 px-2 py-0.5 text-xs font-black">
                            Save €{{ number_format(($comparePriceVal - $basePriceVal) / 100, 2) }}
                        </span>
                    @endif
                    <span class="text-xs text-slate-400 font-semibold">(Retail Unit Price)</span>
                </div>
            </div>

            <!-- Stock Availability -->
            <div class="flex items-center gap-2">
                @if ($inStock)
                    <span class="flex h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-800">
                        In Stock &amp; Ready for Global Dispatch ({{ $variant->stock }} units available)
                    </span>
                @else
                    <span class="flex h-3 w-3 rounded-full bg-rose-500"></span>
                    <span class="text-xs font-bold text-rose-700">Currently Out of Stock</span>
                @endif
            </div>

            <!-- Color Variant Selector -->
            @if (!empty($metadata?->colors) && is_array($metadata->colors))
                <div class="border-t border-slate-100 pt-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Available Color: <span class="text-indigo-600 font-black" x-text="selectedColor"></span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($metadata->colors as $color)
                            <button type="button"
                                    @click="selectedColor = '{{ $color }}'"
                                    :class="selectedColor === '{{ $color }}' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-500/20' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-400'"
                                    class="rounded-xl border px-3.5 py-1.5 text-xs font-bold transition">
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Wholesale Tiered Pricing Matrix -->
            @if ($priceTiers && $priceTiers->count() > 0)
                <div class="rounded-2xl border-2 border-amber-300 bg-amber-50/70 p-4 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-900 flex items-center gap-1.5">
                            <span>📦 Master Carton &amp; Wholesale Volume Tiers</span>
                        </h4>
                        <span class="text-[10px] font-extrabold text-amber-900 bg-amber-200/80 px-2 py-0.5 rounded">B2B Trade Pricing</span>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-amber-200 bg-white">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-amber-100/70 text-amber-950 font-bold border-b border-amber-200">
                                <tr>
                                    <th class="p-2.5">Volume Tier</th>
                                    <th class="p-2.5">Unit Price</th>
                                    <th class="p-2.5">Unit Savings</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-amber-100 font-semibold text-slate-700">
                                <tr>
                                    <td class="p-2.5">1 - 4 units</td>
                                    <td class="p-2.5 font-bold">€{{ number_format($basePriceVal / 100, 2) }}</td>
                                    <td class="p-2.5 text-slate-400">Retail Base</td>
                                </tr>
                                @foreach ($priceTiers as $tier)
                                    @php
                                        $tierSavings = $basePriceVal - $tier->price->value;
                                    @endphp
                                    <tr class="bg-amber-50/40">
                                        <td class="p-2.5 font-bold text-amber-900">
                                            @if ($tier->min_quantity >= 50)
                                                ⭐ {{ $tier->min_quantity }}+ pcs (Master Carton)
                                            @elseif ($tier->min_quantity >= 20)
                                                📦 {{ $tier->min_quantity }}+ pcs (Half Carton)
                                            @else
                                                🔹 {{ $tier->min_quantity }}+ pcs
                                            @endif
                                        </td>
                                        <td class="p-2.5 font-black text-indigo-700">€{{ number_format($tier->price->value / 100, 2) }}</td>
                                        <td class="p-2.5 font-bold text-emerald-600">Save €{{ number_format($tierSavings / 100, 2) }} / unit</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Add to Cart AJAX Form -->
            @if ($variant && $inStock)
                <form action="{{ route('cart.add') }}" method="POST" data-ajax-cart="true" class="space-y-4">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">

                    <div class="flex items-center gap-4">
                        <!-- Interactive Quantity Controller -->
                        <div class="flex items-center rounded-2xl border-2 border-slate-200 bg-slate-50 p-1">
                            <button type="button" @click="if(qty > 1) qty--" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-700 font-black hover:bg-slate-200 transition shadow-xs active:scale-90">
                                -
                            </button>
                            <input type="number" name="quantity" x-model.number="qty" min="1" max="{{ $variant->stock }}" class="w-16 text-center border-0 bg-transparent text-sm font-black text-slate-900 focus:ring-0">
                            <button type="button" @click="if(qty < {{ $variant->stock }}) qty++" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-700 font-black hover:bg-slate-200 transition shadow-xs active:scale-90">
                                +
                            </button>
                        </div>

                        <!-- Add Button -->
                        <button type="submit" class="flex-1 rounded-2xl bg-indigo-600 hover:bg-indigo-500 py-3.5 px-6 text-sm font-black text-white shadow-lg shadow-indigo-600/30 transition active:scale-95 flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add to Cart</span>
                        </button>
                    </div>
                </form>
            @endif

            <!-- Packaging & Carton Logistics Specs -->
            @if ($metadata?->carton_specs)
                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 space-y-1.5">
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                        <span>📦 Master Carton Export Specifications</span>
                    </h4>
                    <p class="text-xs font-mono font-semibold text-slate-700">
                        {{ $metadata->carton_specs }}
                    </p>
                    <p class="text-[11px] text-slate-500">
                        Packaged in reinforced corrugated 5-ply export carton with shock-absorbent internal foam.
                    </p>
                </div>
            @endif

            <!-- Technical Specifications Table -->
            @if (!empty($metadata?->specs) && is_array($metadata->specs))
                <div class="border-t border-slate-200 pt-5 space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Technical Specifications</h3>
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <table class="w-full text-left text-xs">
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach ($metadata->specs as $specKey => $specVal)
                                    <tr class="hover:bg-slate-50">
                                        <td class="p-2.5 font-bold text-slate-500 w-1/3 bg-slate-50/50">{{ $specKey }}</td>
                                        <td class="p-2.5 text-slate-900">{{ $specVal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Product Description -->
            <div class="border-t border-slate-200 pt-5 space-y-2">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Product Overview</h3>
                <div class="text-xs text-slate-600 leading-relaxed space-y-2">
                    {!! nl2br(e($product->translateAttribute('description'))) !!}
                </div>
            </div>

        </div>

    </div>

    <!-- Related Products -->
    @if ($relatedProducts->count() > 0)
        <section class="border-t border-slate-200 pt-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Similar Electronics Models</h2>
                    <p class="text-xs text-slate-500">Other certified products in this collection</p>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($relatedProducts as $relProduct)
                    <x-product-card :product="$relProduct" />
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
