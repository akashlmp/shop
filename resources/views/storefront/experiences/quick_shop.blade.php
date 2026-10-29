@extends('layouts.app')

@section('title', 'Quick Shop - 15 Minute Express Grocery Delivery')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8 pb-28">

    <!-- Quick Commerce Header & Hub Status -->
    <div class="rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <div class="inline-flex items-center gap-2 rounded-full bg-amber-400 px-3 py-0.5 text-[10px] font-black uppercase tracking-wider text-slate-950">
                <span class="h-2 w-2 rounded-full bg-slate-950 animate-ping"></span>
                ⚡ 15-Minute Instant Grocery Dispatch
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Rapid Store: Dispatched in Minutes</h1>
            <p class="text-xs sm:text-sm text-indigo-200 leading-relaxed">
                Need milk, fresh spinach, tomatoes or snacks right now? Our hyperlocal micro-fulfillment centers prepare and dispatch your essentials in minutes.
            </p>
        </div>

        <!-- Pincode / Delivery Area Selector -->
        <div class="rounded-2xl bg-white/10 backdrop-blur-md p-4 border border-white/20 text-xs shrink-0 max-w-sm space-y-2">
            <div class="font-bold text-amber-300 flex items-center justify-between">
                <span>📍 Your Delivery Area:</span>
                <span class="rounded bg-emerald-500 text-white px-1.5 py-0.2 text-[10px] font-extrabold">Open Now</span>
            </div>
            <form action="{{ route('experience.quick') }}" method="GET" class="flex gap-2">
                <select name="postcode" onchange="this.form.submit()" class="rounded-xl border-0 bg-white text-slate-900 text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-amber-400">
                    @foreach ($deliveryAreas as $area)
                        <option value="{{ $area->postcode }}" {{ ($matchedArea?->postcode ?? $selectedPostcode) == $area->postcode ? 'selected' : '' }}>
                            {{ $area->city }} ({{ $area->postcode }}) - ~{{ $area->delivery_time_minutes }}m
                        </option>
                    @endforeach
                </select>
            </form>
            @if ($matchedArea)
                <div class="text-[11px] text-indigo-100 font-semibold">
                    Serving <strong class="text-white">{{ $matchedArea->name }}</strong>. Min order: €{{ number_format($matchedArea->minimum_order_amount / 100, 2) }}.
                </div>
            @endif
        </div>
    </div>

    <!-- Category Quick Filters -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 text-xs font-bold no-scrollbar">
        <a href="{{ route('experience.quick') }}" class="rounded-xl px-4 py-2 transition {{ !request('category') ? 'bg-indigo-600 text-white shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-indigo-400' }}">
            All Quick Essentials ({{ $products->count() }})
        </a>
        @foreach ($collections as $col)
            @php $slug = $col->urls->first()?->slug; @endphp
            <a href="{{ route('experience.quick', ['category' => $slug]) }}" class="rounded-xl px-4 py-2 transition shrink-0 {{ request('category') === $slug ? 'bg-indigo-600 text-white shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-indigo-400' }}">
                {{ $col->translateAttribute('name') }}
            </a>
        @endforeach
    </div>

    <!-- Quick Products Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
        @foreach ($products as $qProduct)
            <x-quick-product-card :product="$qProduct" />
        @endforeach
    </div>

</div>

<!-- Sticky Bottom Cart Bar for Quick Commerce -->
@php
    $cart = \Lunar\Facades\CartSession::current();
    $cartQty = $cart ? $cart->lines()->sum('quantity') : 0;
    $cartTotalFormatted = $cart && $cart->total ? $cart->total->formatted : '€0.00';
@endphp
@if ($cartQty > 0)
    <div class="fixed bottom-0 inset-x-0 z-50 p-4 pointer-events-none">
        <div class="mx-auto max-w-xl pointer-events-auto">
            <div class="flex items-center justify-between rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-700 p-3.5 sm:p-4 text-white shadow-2xl border border-emerald-400/30">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-white font-extrabold text-sm">
                        {{ $cartQty }}
                    </div>
                    <div>
                        <div class="text-xs text-emerald-100 font-semibold">{{ $cartQty }} {{ Str::plural('item', $cartQty) }} in your cart</div>
                        <div class="text-base font-black text-white">{{ $cartTotalFormatted }}</div>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="rounded-xl bg-amber-400 hover:bg-amber-500 py-2.5 px-5 text-xs font-black text-slate-950 shadow transition active:scale-95 flex items-center gap-1.5">
                    <span>Quick Checkout</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>
@endif
@endsection
