@props(['product'])

@php
    $variant = $product->variants->first();
    $basePrice = $variant?->prices->where('min_quantity', 1)->first();
    $tier1Price = $variant?->prices->where('min_quantity', 5)->first();
    $tier2Price = $variant?->prices->where('min_quantity', 20)->first();
    $metadata = $product->relationLoaded('metadata') ? $product->metadata : \App\Models\ProductMetadata::where('product_id', $product->id)->first();
    $url = $product->urls->first()?->slug ?? $product->id;
    $primaryImage = $metadata?->image_url ?? $product->primary_image_url ?? 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80';
    $minQty = $metadata?->bulk_min_quantity ?? 5;

    $basePriceVal = $basePrice ? $basePrice->price->value : 0;
    $tier1Val = $tier1Price ? $tier1Price->price->value : $basePriceVal;
    $tier2Val = $tier2Price ? $tier2Price->price->value : $tier1Val;
    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock >= $minQty);

    $productName = $product->translateAttribute('name') ?? '';
    $brand = 'A&C Certified';
    if (stripos($productName, 'Lenovo') !== false) {
        $brand = 'Lenovo OEM';
    } elseif (stripos($productName, 'AOC') !== false) {
        $brand = 'AOC Gaming';
    }
@endphp

<div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-2xs hover:shadow-xl hover:border-blue-500/40 tech-card-hover transition duration-300">
    
    <!-- Top B2B Badge Header -->
    <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100 text-xs">
        <span class="inline-flex items-center gap-1 font-bold text-blue-800 bg-blue-50 px-2.5 py-0.5 rounded-md text-[10px] uppercase tracking-wider border border-blue-200/50">
            📦 Master Carton &bull; Min {{ $minQty }} Pcs
        </span>
        <span class="font-bold text-slate-500 text-[10px]">
            {{ $brand }}
        </span>
    </div>

    <!-- Product Image -->
    <div class="relative w-full overflow-hidden rounded-xl bg-slate-100 aspect-[4/5] mb-3">
        <a href="{{ route('products.show', $url) }}" class="block h-full w-full">
            <img src="{{ $primaryImage }}" alt="{{ $product->translateAttribute('name') }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-500" loading="lazy">
        </a>
    </div>

    <!-- Details -->
    <div class="flex-1 flex flex-col justify-between">
        <div>
            <div class="text-[10px] font-bold uppercase tracking-wider text-blue-600 mb-1">
                Direct OEM Sourcing &bull; Export Grade
            </div>
            
            <h3 class="font-display text-sm font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug">
                <a href="{{ route('products.show', $url) }}">
                    {{ $product->translateAttribute('name') }}
                </a>
            </h3>

            <!-- Tiered Price Matrix -->
            <div class="mt-3 rounded-xl bg-slate-50 p-3 border border-slate-200 text-xs space-y-1.5">
                <div class="flex justify-between items-center text-[11px] text-slate-500">
                    <span>Retail Unit Price:</span>
                    <span class="font-semibold text-slate-900">£{{ number_format($basePriceVal / 100, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-[11px] font-semibold text-blue-950 bg-blue-100/70 px-2 py-0.5 rounded-md">
                    <span>5 - 19 units (Carton Tier):</span>
                    <span class="font-bold text-blue-900">£{{ number_format($tier1Val / 100, 2) }} / pc</span>
                </div>
                <div class="flex justify-between items-center text-[11px] font-bold text-emerald-950 bg-emerald-100/70 px-2 py-0.5 rounded-md">
                    <span>20+ units (Master Consignment):</span>
                    <span class="font-black text-emerald-900">£{{ number_format($tier2Val / 100, 2) }} / pc</span>
                </div>
            </div>
        </div>

        <!-- Add Bulk Form with AJAX support -->
        <div class="mt-4 pt-3 border-t border-slate-100">
            @if ($variant && $inStock)
                <form action="{{ route('cart.add') }}" method="POST" data-ajax-cart="true" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                    <input type="hidden" name="is_bulk" value="1">
                    <input type="number" name="quantity" value="{{ $minQty }}" min="{{ $minQty }}" class="w-16 rounded-xl border border-slate-300 py-1.5 px-2 text-center text-xs font-bold text-slate-900 focus:border-blue-600">
                    <button type="submit" class="flex-1 rounded-xl bg-slate-950 py-2 text-center text-xs font-bold text-white hover:bg-blue-600 transition shadow-sm">
                        Order Carton &rarr;
                    </button>
                </form>
            @else
                <button type="button" disabled class="w-full rounded-xl bg-slate-100 py-2 text-xs font-semibold text-slate-400 cursor-not-allowed">
                    Sold Out
                </button>
            @endif
        </div>
    </div>
</div>
