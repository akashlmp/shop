@extends('layouts.app')

@section('title', $product->translateAttribute('name') . ' - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs font-semibold text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-slate-800 transition">Products</a>
        @if ($primaryColl = $product->collections->first())
            <span>/</span>
            <a href="{{ route('products.category', $primaryColl->urls->first()?->slug ?? '') }}" class="hover:text-slate-800 transition">
                {{ $primaryColl->translateAttribute('name') }}
            </a>
        @endif
        <span>/</span>
        <span class="text-slate-900 font-bold line-clamp-1">{{ $product->translateAttribute('name') }}</span>
    </nav>

    <!-- Product Showcase & Purchase -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
        
        <!-- Left: Product Image -->
        <div class="lg:col-span-6 space-y-4">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-white aspect-square shadow-sm">
                <img src="{{ $metadata?->image_url ?? $product->primary_image_url }}" alt="{{ $product->translateAttribute('name') }}" class="h-full w-full object-cover object-center">
                
                <div class="absolute top-4 left-4 flex flex-col gap-2">
                    @if ($metadata?->badge_text)
                        <span class="rounded-lg bg-emerald-600 px-3 py-1 text-xs font-black text-white shadow">
                            {{ $metadata->badge_text }}
                        </span>
                    @endif
                    @if ($metadata?->is_quick_commerce)
                        <span class="rounded-lg bg-amber-400 px-2.5 py-1 text-[11px] font-black text-slate-950 shadow">
                            ⚡ Express {{ $metadata->quick_delivery_minutes ?? 15 }}m Delivery
                        </span>
                    @endif
                </div>
            </div>

            <!-- Fast Guarantees -->
            <div class="grid grid-cols-3 gap-3 text-center text-xs font-bold text-slate-600">
                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xs">
                    <span class="text-emerald-600 block text-base mb-1">🌿</span>
                    100% Authentic
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xs">
                    <span class="text-emerald-600 block text-base mb-1">❄️</span>
                    Cold-Chain Fresh
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xs">
                    <span class="text-emerald-600 block text-base mb-1">↩️</span>
                    Doorstep Return
                </div>
            </div>
        </div>

        <!-- Right: Purchasing Controls -->
        <div class="lg:col-span-6 space-y-6">
            
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                    <span>SKU: {{ $variant?->sku ?? 'N/A' }}</span>
                    <span>&bull;</span>
                    <span>Unit: {{ ucfirst($metadata?->unit_of_measure ?? 'piece') }}</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                    {{ $product->translateAttribute('name') }}
                </h1>

                <!-- Pricing Display -->
                @php
                    $basePriceVal = $basePrice ? $basePrice->price->value : 0;
                    $comparePriceVal = $basePrice && $basePrice->compare_price ? $basePrice->compare_price->value : null;
                    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock > 0);
                @endphp
                <div class="mt-4 flex items-baseline gap-3">
                    <span class="text-3xl font-black text-slate-900">
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
                </div>
            </div>

            <!-- Stock Availability Pill -->
            <div class="flex items-center gap-2">
                @if ($inStock)
                    <span class="flex h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-800">
                        In Stock &amp; Ready for Immediate Dispatch ({{ $variant->stock }} units in local hub)
                    </span>
                @else
                    <span class="flex h-3 w-3 rounded-full bg-rose-500"></span>
                    <span class="text-xs font-bold text-rose-700">Currently Out of Stock</span>
                @endif
            </div>

            <!-- Tiered Bulk Volume Pricing Table -->
            @if ($priceTiers && $priceTiers->count() > 0)
                <div class="rounded-2xl border-2 border-amber-300 bg-amber-50/60 p-4 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-900 flex items-center gap-1.5">
                            <span>📦 Wholesale &amp; Bulk Pricing Tiers</span>
                        </h4>
                        <span class="text-[10px] font-extrabold text-amber-800 bg-amber-200/70 px-2 py-0.5 rounded">Automatic Volume Discount</span>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-amber-200 bg-white">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-amber-100/60 text-amber-900 font-bold border-b border-amber-200">
                                <tr>
                                    <th class="p-2">Quantity Bracket</th>
                                    <th class="p-2">Price Per Unit</th>
                                    <th class="p-2">Your Savings</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-amber-100 font-semibold text-slate-700">
                                <tr>
                                    <td class="p-2">1 - 4 units</td>
                                    <td class="p-2 font-bold">€{{ number_format($basePriceVal / 100, 2) }}</td>
                                    <td class="p-2 text-slate-400">Standard Price</td>
                                </tr>
                                @foreach ($priceTiers as $tier)
                                    @php
                                        $tierSavings = $basePriceVal - $tier->price->value;
                                    @endphp
                                    <tr class="bg-amber-50/30">
                                        <td class="p-2 font-bold text-amber-900">{{ $tier->min_quantity }}+ units</td>
                                        <td class="p-2 font-extrabold text-emerald-700">€{{ number_format($tier->price->value / 100, 2) }}</td>
                                        <td class="p-2 font-bold text-emerald-600">Save €{{ number_format($tierSavings / 100, 2) }} / unit</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Add to Cart Form -->
            @if ($variant && $inStock)
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4" x-data="{ qty: 1 }">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">

                    <div class="flex items-center gap-4">
                        <!-- Interactive Quantity Controller -->
                        <div class="flex items-center rounded-2xl border-2 border-slate-200 bg-slate-50 p-1">
                            <button type="button" @click="if(qty > 1) qty--" class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-slate-700 font-extrabold hover:bg-slate-200 transition shadow-xs active:scale-90">
                                -
                            </button>
                            <input type="number" name="quantity" x-model.number="qty" min="1" max="{{ $variant->stock }}" class="w-14 text-center border-0 bg-transparent text-sm font-black text-slate-900 focus:ring-0">
                            <button type="button" @click="if(qty < {{ $variant->stock }}) qty++" class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-slate-700 font-extrabold hover:bg-slate-200 transition shadow-xs active:scale-90">
                                +
                            </button>
                        </div>

                        <!-- Add Button -->
                        <button type="submit" class="flex-1 rounded-2xl bg-emerald-600 hover:bg-emerald-700 py-3.5 px-6 text-sm font-black text-white shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add to Shopping Cart</span>
                        </button>
                    </div>
                </form>
            @endif

            <!-- Sourcing & Farmer Origin Details -->
            @if ($metadata?->origin_details)
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 space-y-1.5">
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                        <span>🌱 Origin &amp; Sourcing Authenticity</span>
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $metadata->origin_details }}
                    </p>
                </div>
            @endif

            <!-- Product Description -->
            <div class="border-t border-slate-200 pt-6 space-y-3">
                <h3 class="text-sm font-bold text-slate-900">About this Product</h3>
                <div class="text-xs text-slate-600 leading-relaxed space-y-2">
                    {!! nl2br(e($product->translateAttribute('description'))) !!}
                </div>
            </div>

        </div>

    </div>

    <!-- Related Products -->
    @if ($relatedProducts->count() > 0)
        <section class="border-t border-slate-200 pt-10">
            <h2 class="text-xl font-black text-slate-900 mb-6">Frequently Bought Together</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($relatedProducts as $relProduct)
                    <x-product-card :product="$relProduct" />
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
