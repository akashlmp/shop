@props(['product'])

@php
    $variant = $product->variants->first();
    $basePrice = $variant?->prices->where('min_quantity', 1)->first();
    $metadata = $product->relationLoaded('metadata') ? $product->metadata : \App\Models\ProductMetadata::where('product_id', $product->id)->first();
    $url = $product->urls->first()?->slug ?? $product->id;
    $primaryImage = $metadata?->image_url ?? $product->primary_image_url;
    $priceFormatted = $basePrice ? '€' . number_format($basePrice->price->value / 100, 2) : '€0.00';
    $inStock = $variant && ($variant->purchasable === 'always' || $variant->stock > 0);
@endphp

<div class="group relative flex flex-col justify-between rounded-xl border border-slate-200/90 bg-white p-3 shadow-sm hover:shadow hover:border-indigo-300 transition">
    
    <div class="relative w-full aspect-square overflow-hidden rounded-lg bg-slate-50 mb-2">
        <a href="{{ route('products.show', $url) }}" class="block h-full w-full">
            <img src="{{ $primaryImage }}" alt="{{ $product->translateAttribute('name') }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-200" loading="lazy">
        </a>
        <div class="absolute top-1.5 left-1.5 rounded bg-indigo-600 px-1.5 py-0.5 text-[9px] font-black text-white shadow">
            ⚡ {{ $metadata?->quick_delivery_minutes ?? 15 }} MINS
        </div>
    </div>

    <div>
        <div class="text-[10px] font-bold text-slate-400 mb-0.5 uppercase tracking-wide">
            {{ $metadata?->unit_of_measure ?? 'piece' }}
        </div>
        <h4 class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition line-clamp-2 leading-tight">
            <a href="{{ route('products.show', $url) }}">
                {{ $product->translateAttribute('name') }}
            </a>
        </h4>
        <div class="mt-1 text-sm font-extrabold text-slate-900">
            {{ $priceFormatted }}
        </div>
    </div>

    <div class="mt-2.5 pt-2 border-t border-slate-100">
        @if ($variant && $inStock)
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full rounded-lg bg-indigo-50 border border-indigo-200 py-1.5 text-xs font-extrabold text-indigo-700 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center gap-1 active:scale-95">
                    <span>+ ADD</span>
                </button>
            </form>
        @else
            <button disabled class="w-full rounded-lg bg-slate-100 py-1.5 text-xs font-bold text-slate-400 cursor-not-allowed">
                Out
            </button>
        @endif
    </div>
</div>

