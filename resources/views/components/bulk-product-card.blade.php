@props(['product'])

@php
    $variant = $product->variants->first();
    $basePrice = $variant?->prices->where('min_quantity', 1)->first();
    $tier1Price = $variant?->prices->where('min_quantity', 5)->first();
    $tier2Price = $variant?->prices->where('min_quantity', 20)->first();
    $tier3Price = $variant?->prices->where('min_quantity', 50)->first() ?? $variant?->prices->where('min_quantity', '>=', 50)->sortBy('price')->first();
    $metadata = $product->relationLoaded('metadata') ? $product->metadata : \App\Models\ProductMetadata::where('product_id', $product->id)->first();
    $url = $product->urls->first()?->slug ?? $product->id;
    $primaryImage = $metadata?->image_url ?? $product->primary_image_url;
    $minQty = $metadata?->bulk_min_quantity ?? 5;

    $basePriceVal = $basePrice ? $basePrice->price->value : 0;
    $tier1Val = $tier1Price ? $tier1Price->price->value : $basePriceVal;
    $tier2Val = $tier2Price ? $tier2Price->price->value : $tier1Val;
    $tier3Val = $tier3Price ? $tier3Price->price->value : $tier2Val;

    $maxSavingsPercent = $basePriceVal > 0 ? round((($basePriceVal - $tier3Val) / $basePriceVal) * 100) : 0;
    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock >= $minQty);
@endphp

<div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border-2 border-amber-200/90 bg-white p-4 shadow-sm hover:shadow-xl hover:border-amber-400 transition duration-300">
    
    <!-- Top Wholesale Banner -->
    <div class="flex items-center justify-between pb-2 mb-2 border-b border-amber-100 text-xs">
        <span class="inline-flex items-center gap-1 font-black text-amber-950 bg-amber-200/80 px-2 py-0.5 rounded-md text-[10px] uppercase tracking-wider">
            📦 MASTER CARTON B2B
        </span>
        @if ($maxSavingsPercent > 0)
            <span class="font-black text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md text-[10px]">
                SAVE UP TO {{ $maxSavingsPercent }}%
            </span>
        @endif
    </div>

    <!-- Product Image & Badges -->
    <div class="relative w-full overflow-hidden rounded-xl bg-slate-900/5 aspect-square mb-3">
        <a href="{{ route('products.show', $url) }}" class="block h-full w-full">
            <img src="{{ $primaryImage }}" alt="{{ $product->translateAttribute('name') }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-500" loading="lazy">
        </a>
        <div class="absolute bottom-2 left-2 rounded-md bg-slate-950/85 backdrop-blur-sm px-2 py-0.5 text-[10px] font-bold text-white shadow border border-slate-800">
            Min Order: {{ $minQty }} Pcs
        </div>
        @if ($metadata?->brand)
            <div class="absolute top-2 left-2 rounded-md bg-slate-900/90 px-2 py-0.5 text-[10px] font-black text-white shadow">
                {{ strtoupper($metadata->brand) }}
            </div>
        @endif
    </div>

    <!-- Details -->
    <div class="flex-1 flex flex-col justify-between">
        <div>
            <div class="text-[11px] font-semibold text-indigo-600 mb-1">
                {{ $metadata?->bulk_package_type ?? 'Export Carton Packaging' }}
            </div>
            
            <h3 class="text-sm font-bold text-slate-900 group-hover:text-amber-700 transition line-clamp-2 leading-tight">
                <a href="{{ route('products.show', $url) }}">
                    {{ $product->translateAttribute('name') }}
                </a>
            </h3>

            @if ($metadata?->carton_specs)
                <div class="mt-1 text-[10px] font-mono text-slate-500 line-clamp-1">
                    {{ $metadata->carton_specs }}
                </div>
            @endif

            <!-- Tiered Price Matrix Table -->
            <div class="mt-3 rounded-xl bg-amber-50/70 p-2.5 border border-amber-200/80 text-xs space-y-1">
                <div class="flex justify-between items-center text-[11px] font-semibold text-slate-600">
                    <span>1 - 4 units (Retail):</span>
                    <span class="font-bold text-slate-800">€{{ number_format($basePriceVal / 100, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-[11px] font-bold text-amber-900 bg-amber-100/60 px-1.5 py-0.5 rounded">
                    <span>5 - 19 units:</span>
                    <span class="text-indigo-700">€{{ number_format($tier1Val / 100, 2) }} / pc</span>
                </div>
                <div class="flex justify-between items-center text-[11px] font-extrabold text-amber-950 bg-amber-200/60 px-1.5 py-0.5 rounded">
                    <span>20 - 49 units:</span>
                    <span class="text-indigo-800">€{{ number_format($tier2Val / 100, 2) }} / pc</span>
                </div>
                @if ($tier3Val < $tier2Val)
                    <div class="flex justify-between items-center text-[11px] font-black text-emerald-950 bg-emerald-100/80 px-1.5 py-0.5 rounded border border-emerald-300/60">
                        <span>50+ units (Master):</span>
                        <span class="text-emerald-800 font-black">€{{ number_format($tier3Val / 100, 2) }} / pc</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Add Bulk Form with AJAX support -->
        <div class="mt-4 pt-3 border-t border-slate-100">
            @if ($variant && $inStock)
                <form action="{{ route('cart.add') }}" method="POST" data-ajax-cart="true" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                    <input type="hidden" name="is_bulk" value="1">
                    
                    <div class="w-28">
                        <select name="quantity" class="w-full rounded-xl border border-slate-300 py-2 px-2 text-xs font-bold text-slate-800 focus:border-amber-500 focus:ring-amber-500">
                            <option value="{{ $minQty }}">{{ $minQty }} pcs</option>
                            <option value="20">20 pcs (Tier 2)</option>
                            <option value="50">50 pcs (Carton)</option>
                            <option value="100">100 pcs (Double)</option>
                        </select>
                    </div>

                    <button type="submit" class="flex-1 rounded-xl bg-amber-400 hover:bg-amber-300 py-2 px-3 text-xs font-black text-slate-950 shadow-sm transition active:scale-95 flex items-center justify-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Add Bulk</span>
                    </button>
                </form>
            @else
                <button type="button" disabled class="w-full rounded-xl bg-slate-100 py-2 text-xs font-bold text-slate-400 cursor-not-allowed">
                    Wholesale Stock Unavailable
                </button>
            @endif
        </div>
    </div>
</div>
