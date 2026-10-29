@props(['product'])

@php
    $variant = $product->variants->first();
    $basePrice = $variant?->prices->where('min_quantity', 1)->first();
    $metadata = $product->relationLoaded('metadata') ? $product->metadata : \App\Models\ProductMetadata::where('product_id', $product->id)->first();
    $url = $product->urls->first()?->slug ?? $product->id;
    $primaryImage = $metadata?->image_url ?? $product->primary_image_url;
    $priceFormatted = $basePrice ? '€' . number_format($basePrice->price->value / 100, 2) : '€0.00';
    $comparePriceFormatted = $basePrice && $basePrice->compare_price ? '€' . number_format($basePrice->compare_price->value / 100, 2) : null;
    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock > 0);
@endphp

<div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3.5 sm:p-4 shadow-sm hover:shadow-md hover:border-emerald-300 transition duration-200">
    
    <!-- Top Badges -->
    <div class="relative w-full overflow-hidden rounded-xl bg-slate-50 aspect-square mb-3">
        <a href="{{ route('products.show', $url) }}" class="block h-full w-full">
            <img src="{{ $primaryImage }}" alt="{{ $product->translateAttribute('name') }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300" loading="lazy">
        </a>

        <!-- Badges Overlay -->
        <div class="absolute top-2 left-2 flex flex-col gap-1">
            @if ($metadata?->badge_text)
                <span class="inline-flex items-center rounded-md bg-emerald-600/90 backdrop-blur-sm px-2 py-0.5 text-[10px] font-bold text-white shadow-sm">
                    {{ $metadata->badge_text }}
                </span>
            @endif
            @if ($metadata?->is_quick_commerce)
                <span class="inline-flex items-center gap-1 rounded-md bg-amber-400 px-1.5 py-0.5 text-[9px] font-black text-slate-900 shadow-sm">
                    ⚡ {{ $metadata->quick_delivery_minutes ?? 15 }}m
                </span>
            @endif
        </div>

        @if ($comparePriceFormatted)
            <div class="absolute bottom-2 right-2 rounded-md bg-rose-500 px-1.5 py-0.5 text-[10px] font-black text-white shadow">
                SALE
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="flex-1 flex flex-col justify-between">
        <div>
            <!-- Unit & Category -->
            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-400 mb-1">
                <span>{{ ucfirst($metadata?->unit_of_measure ?? 'piece') }}</span>
                @if ($inStock)
                    <span class="text-emerald-600 font-bold">In Stock</span>
                @else
                    <span class="text-rose-500 font-bold">Out of Stock</span>
                @endif
            </div>

            <!-- Title -->
            <h3 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition line-clamp-2 leading-tight">
                <a href="{{ route('products.show', $url) }}">
                    {{ $product->translateAttribute('name') }}
                </a>
            </h3>

            <!-- Price & Strikethrough -->
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-base font-extrabold text-slate-900">{{ $priceFormatted }}</span>
                @if ($comparePriceFormatted)
                    <span class="text-xs text-slate-400 line-through">{{ $comparePriceFormatted }}</span>
                @endif
            </div>
        </div>

        <!-- Add to Cart Form -->
        <div class="mt-3.5 pt-3 border-t border-slate-100">
            @if ($variant && $inStock)
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 rounded-xl border-2 border-emerald-600 bg-emerald-50/60 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-600 hover:text-white transition shadow-sm active:scale-95">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>ADD</span>
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
