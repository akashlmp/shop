@props(['product'])

@php
    $variant = $product->variants->first();
    $basePrice = $variant?->prices->where('min_quantity', 1)->first();
    $bulkPrice = $variant?->prices->where('min_quantity', '>=', 5)->sortBy('price')->first();
    $metadata = $product->relationLoaded('metadata') ? $product->metadata : \App\Models\ProductMetadata::where('product_id', $product->id)->first();
    $url = $product->urls->first()?->slug ?? $product->id;
    $primaryImage = $metadata?->image_url ?? $product->primary_image_url;
    $priceFormatted = $basePrice ? '€' . number_format($basePrice->price->value / 100, 2) : '€0.00';
    $comparePriceFormatted = $basePrice && $basePrice->compare_price ? '€' . number_format($basePrice->compare_price->value / 100, 2) : null;
    $bulkPriceFormatted = $bulkPrice ? '€' . number_format($bulkPrice->price->value / 100, 2) : null;
    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock > 0);
    $colors = $metadata?->colors ?? [];
@endphp

<div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-3.5 sm:p-4 shadow-sm hover:shadow-xl hover:border-indigo-400/80 transition duration-300">
    
    <!-- Top Image Container -->
    <div class="relative w-full overflow-hidden rounded-xl bg-slate-900/5 aspect-square mb-3.5">
        <a href="{{ route('products.show', $url) }}" class="block h-full w-full">
            <img src="{{ $primaryImage }}"
                 alt="{{ $product->translateAttribute('name') }}"
                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-500 ease-out"
                 loading="lazy">
        </a>

        <!-- Top Badges Overlay -->
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 items-start">
            @if ($metadata?->brand)
                <span class="inline-flex items-center rounded-md bg-slate-950/85 backdrop-blur-md px-2 py-0.5 text-[10px] font-black tracking-wide text-white shadow-sm border border-slate-700/50">
                    {{ strtoupper($metadata->brand) }}
                </span>
            @endif
            @if ($metadata?->badge_text)
                <span class="inline-flex items-center rounded-md bg-indigo-600/90 backdrop-blur-md px-2 py-0.5 text-[9px] font-bold text-white shadow-sm">
                    {{ $metadata->badge_text }}
                </span>
            @endif
        </div>

        @if ($comparePriceFormatted)
            <div class="absolute bottom-2.5 right-2.5 rounded-md bg-rose-600 px-1.5 py-0.5 text-[10px] font-black text-white shadow">
                SALE
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="flex-1 flex flex-col justify-between">
        <div>
            <!-- Chipset / Spec Pill -->
            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500 mb-1.5">
                <span class="truncate max-w-[170px] text-indigo-600 font-bold">
                    {{ $metadata?->chipset ?? ($metadata?->model_code ? 'Model ' . $metadata->model_code : 'Certified Hardware') }}
                </span>
                @if ($inStock)
                    <span class="text-emerald-600 font-bold flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Stock
                    </span>
                @else
                    <span class="text-rose-500 font-bold">Out of Stock</span>
                @endif
            </div>

            <!-- Title -->
            <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition line-clamp-2 leading-snug">
                <a href="{{ route('products.show', $url) }}">
                    {{ $product->translateAttribute('name') }}
                </a>
            </h3>

            <!-- Color Variant Dots Preview -->
            @if (!empty($colors) && is_array($colors))
                <div class="mt-2 flex items-center gap-1">
                    <span class="text-[10px] text-slate-400 mr-1">Colors:</span>
                    @foreach (array_slice($colors, 0, 3) as $c)
                        <span class="inline-block rounded-md bg-slate-100 border border-slate-200 px-1.5 py-0.2 text-[9px] font-semibold text-slate-600">
                            {{ $c }}
                        </span>
                    @endforeach
                    @if (count($colors) > 3)
                        <span class="text-[9px] text-slate-400">+{{ count($colors) - 3 }}</span>
                    @endif
                </div>
            @endif

            <!-- Price & B2B Tier Badge -->
            <div class="mt-3 flex items-baseline justify-between">
                <div>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-lg font-black text-slate-950">{{ $priceFormatted }}</span>
                        @if ($comparePriceFormatted)
                            <span class="text-xs text-slate-400 line-through">{{ $comparePriceFormatted }}</span>
                        @endif
                    </div>
                    <span class="text-[10px] text-slate-400">Retail Unit Price</span>
                </div>

                @if ($bulkPriceFormatted)
                    <div class="text-right">
                        <span class="text-xs font-black text-amber-600">{{ $bulkPriceFormatted }}</span>
                        <div class="text-[9px] font-bold text-slate-400">Carton Price</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Add to Cart AJAX Form -->
        <div class="mt-4 pt-3 border-t border-slate-100">
            @if ($variant && $inStock)
                <form action="{{ route('cart.add') }}" method="POST" data-ajax-cart="true">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-1.5 rounded-xl border border-indigo-600 bg-indigo-50/80 py-2 px-3 text-xs font-bold text-indigo-700 hover:bg-indigo-600 hover:text-white transition duration-150 shadow-sm active:scale-95">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>ADD TO CART</span>
                    </button>
                </form>
            @else
                <button type="button" disabled class="w-full rounded-xl bg-slate-100 py-2 text-xs font-bold text-slate-400 cursor-not-allowed">
                    Out of Stock
                </button>
            @endif
        </div>
    </div>
</div>
