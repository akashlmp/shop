@props(['product'])

@php
    $variant = $product->variants->first();
    $basePrice = $variant?->prices->where('min_quantity', 1)->first();
    $metadata = $product->relationLoaded('metadata') ? $product->metadata : \App\Models\ProductMetadata::where('product_id', $product->id)->first();
    $url = $product->urls->first()?->slug ?? $product->id;
    $primaryImage = $metadata?->image_url ?? $product->primary_image_url ?? 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80';
    $priceFormatted = $basePrice ? '£' . number_format($basePrice->price->value / 100, 2) : '£0.00';
    $comparePriceFormatted = $basePrice && $basePrice->compare_price ? '£' . number_format($basePrice->compare_price->value / 100, 2) : null;
    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock > 0);
    $categoryName = $product->collections->first()?->translateAttribute('name') ?? 'Consumer Electronics';
    
    // Brand detection
    $productName = $product->translateAttribute('name') ?? '';
    $brand = 'A&C Certified';
    if (stripos($productName, 'Lenovo') !== false) {
        $brand = 'Lenovo OEM';
    } elseif (stripos($productName, 'AOC') !== false) {
        $brand = 'AOC Gaming';
    }
@endphp

<div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-3 sm:p-4 shadow-2xs hover:shadow-xl hover:border-blue-500/40 tech-card-hover transition duration-300">
    
    <!-- Top Image Container (Portrait 4:5 Aspect Ratio) -->
    <div class="relative w-full overflow-hidden rounded-xl bg-slate-100 aspect-[4/5] mb-3">
        <a href="{{ route('products.show', $url) }}" class="block h-full w-full">
            <img src="{{ $primaryImage }}"
                 alt="{{ $product->translateAttribute('name') }}"
                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-500 ease-out"
                 loading="lazy">
        </a>

        <!-- Top Badges Overlay -->
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 items-start">
            <span class="inline-flex items-center rounded-md bg-slate-950/85 backdrop-blur-xs px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white shadow-2xs border border-white/10">
                {{ $brand }}
            </span>
            @if ($comparePriceFormatted)
                <span class="inline-flex items-center rounded-md bg-blue-600 px-2 py-0.5 text-[9px] font-bold text-white shadow-2xs">
                    Save £{{ number_format(($basePrice->compare_price->value - $basePrice->price->value) / 100, 2) }}
                </span>
            @endif
        </div>

        <!-- In-Stock Pulse Light -->
        <div class="absolute top-2.5 right-2.5">
            @if ($inStock)
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 backdrop-blur-xs px-2 py-0.5 text-[9px] font-bold text-white shadow-xs">
                    <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                    UK Stock
                </span>
            @endif
        </div>

        <!-- Quick View Overlay on Hover (Desktop) -->
        <div class="hidden lg:flex absolute inset-x-2 bottom-2 justify-center opacity-0 group-hover:opacity-100 transition duration-200">
            <a href="{{ route('products.show', $url) }}"
               class="w-full text-center rounded-xl bg-slate-900/90 backdrop-blur-xs py-2 text-[11px] font-semibold tracking-wider uppercase text-white hover:bg-blue-600 shadow-md transition">
                View Specifications &rarr;
            </a>
        </div>
    </div>

    <!-- Product Info Details -->
    <div class="flex-1 flex flex-col justify-between">
        <div>
            <!-- Star Rating & Category -->
            <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                <span class="text-blue-700 font-semibold truncate max-w-[130px] uppercase tracking-wider text-[9px]">
                    {{ $categoryName }}
                </span>
                <div class="flex items-center gap-1 text-amber-500 text-[10px]">
                    <span>★★★★★</span>
                    <span class="text-slate-400 text-[9px]">4.9</span>
                </div>
            </div>

            <!-- Product Title -->
            <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug">
                <a href="{{ route('products.show', $url) }}">
                    {{ $product->translateAttribute('name') }}
                </a>
            </h3>

            <!-- Tech Specification Tag -->
            <p class="mt-1 text-[10px] font-medium text-slate-500 flex items-center gap-1">
                <span class="text-blue-600">⚡</span>
                <span>CE &bull; RoHS Verified &bull; 1-Yr UK Warranty</span>
            </p>

            <!-- Price Display & Wholesale Margin Pill -->
            <div class="mt-2.5 flex items-baseline justify-between">
                <div class="flex items-baseline gap-2">
                    <span class="font-display text-base sm:text-lg font-black text-slate-950">{{ $priceFormatted }}</span>
                    @if ($comparePriceFormatted)
                        <span class="text-xs text-slate-400 line-through">{{ $comparePriceFormatted }}</span>
                    @endif
                </div>
                <span class="text-[9px] font-bold text-blue-700 bg-blue-50 border border-blue-200/60 px-1.5 py-0.5 rounded">
                    B2B Carton Available
                </span>
            </div>
        </div>

        <!-- Add to Bag Form with AJAX support -->
        <div class="mt-3 pt-2.5 border-t border-slate-100">
            @if ($variant && $inStock)
                <form action="{{ route('cart.add') }}" method="POST" data-ajax-cart="true">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-1.5 rounded-full border border-slate-900 bg-white py-2 px-3 text-[11px] font-bold text-slate-900 hover:bg-blue-600 hover:border-blue-600 hover:text-white transition duration-200 active:scale-98 shadow-2xs">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Add to Bag</span>
                    </button>
                </form>
            @else
                <button type="button" disabled class="w-full rounded-full bg-slate-100 py-2 text-[11px] font-semibold text-slate-400 cursor-not-allowed">
                    Out of Stock
                </button>
            @endif
        </div>
    </div>
</div>
