@extends('layouts.app')

@section('title', 'Shopping Bag - A&C Electronics')
@section('meta_description', 'Review your selected electronic devices, Lenovo & AOC hardware, and master carton quantities in your A&C Electronics bag.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Shopping Bag</span>
    </nav>

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-slate-950">Your Hardware Bag</h1>
            <p class="text-xs text-slate-500 mt-1">Review your selected electronics, unit quantities, and master carton allocations</p>
        </div>
        @if ($summary['total_items'] > 0)
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Clear your entire bag?')" class="text-xs font-semibold text-slate-400 hover:text-slate-800 transition">
                    Clear Bag
                </button>
            </form>
        @endif
    </div>

    @if ($summary['total_items'] > 0)
        <!-- Tracked Delivery Progress Banner -->
        <div class="rounded-2xl bg-blue-50 border border-blue-200 p-4 text-xs text-blue-950 flex flex-col sm:flex-row items-center justify-between gap-2 shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="text-base">🚚</span>
                <span class="font-semibold">Tracked Express UK Dispatch &bull; CE &amp; RoHS Certified Batch</span>
            </div>
            <span class="font-bold text-blue-800 bg-blue-100/90 px-3 py-1 rounded-full text-[11px]">Direct OEM Verified ✓</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Cart Line Items -->
            <div class="lg:col-span-8 space-y-4">
                
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xs">
                    <div class="divide-y divide-slate-100">
                        @foreach ($summary['lines'] as $line)
                            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                
                                <!-- Product Info & Image -->
                                <div class="flex items-center gap-4">
                                    <div class="h-20 w-20 sm:h-24 sm:w-24 shrink-0 overflow-hidden rounded-2xl bg-slate-100 border border-slate-200">
                                        <img src="{{ $line['image_url'] }}" alt="{{ $line['name'] }}" class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-200/60 px-2 py-0.5 rounded">
                                            A&amp;C Certified Hardware
                                        </span>
                                        <h3 class="font-display text-sm sm:text-base font-bold text-slate-900 leading-snug">
                                            {{ $line['name'] }}
                                        </h3>
                                        <p class="text-[11px] text-slate-500">
                                            Unit Price: <span class="font-bold text-slate-900">{{ $line['unit_price_formatted'] }}</span>
                                        </p>
                                        <p class="text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                                            <span>⚡ 12-Month UK Replacement Warranty Included</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Quantity Controls & Line Total -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                                    
                                    <!-- Quantity Update Form -->
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center rounded-full border border-slate-300 bg-white p-1">
                                        @csrf
                                        <input type="hidden" name="line_id" value="{{ $line['id'] }}">
                                        
                                        <button type="submit" name="quantity" value="{{ $line['quantity'] - 1 }}" class="h-7 w-7 rounded-full bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 flex items-center justify-center transition">
                                            -
                                        </button>
                                        <span class="w-8 text-center text-xs font-bold text-slate-900">
                                            {{ $line['quantity'] }}
                                        </span>
                                        <button type="submit" name="quantity" value="{{ $line['quantity'] + 1 }}" class="h-7 w-7 rounded-full bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 flex items-center justify-center transition">
                                            +
                                        </button>
                                    </form>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <div class="font-display text-base font-black text-slate-950">
                                            {{ $line['sub_total_formatted'] }}
                                        </div>
                                    </div>

                                    <!-- Remove Item -->
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="line_id" value="{{ $line['id'] }}">
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 transition p-1" title="Remove Item">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Export Packaging Assurance -->
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-2xs flex items-center gap-3">
                    <span class="text-2xl">📦</span>
                    <div class="text-xs">
                        <span class="font-bold text-slate-900 block">Export-Grade Shockproof Packaging</span>
                        <span class="text-slate-500">All consignments packed in double-wall protective cartons with ESD antistatic shielding.</span>
                    </div>
                </div>

            </div>

            <!-- Right: Order Cost Breakdown Summary -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Trade Coupon Box -->
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-2xs space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                        Trade Coupon / Voucher Code
                    </h3>

                    @if ($summary['coupon_code'])
                        <div class="flex items-center justify-between rounded-xl bg-blue-50 p-3 border border-blue-200">
                            <div class="text-xs font-semibold text-blue-900">
                                Trade Code <span class="font-mono uppercase font-bold">{{ $summary['coupon_code'] }}</span> Applied!
                            </div>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-rose-700 hover:text-rose-900">
                                    Remove
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="Enter coupon (e.g. WELCOME10)" class="flex-1 rounded-full border border-slate-300 px-3.5 py-2 text-xs font-semibold uppercase text-slate-900 focus:border-blue-600 focus:outline-none">
                            <button type="submit" class="rounded-full bg-blue-600 hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition">
                                Apply
                            </button>
                        </form>
                        <p class="text-[10px] text-slate-400">Use <strong class="text-blue-600 font-mono">WELCOME10</strong> (10% off) or <strong class="text-blue-600 font-mono">B2B50</strong> for wholesale shipping credits.</p>
                    @endif
                </div>

                <!-- Totals Box -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-4">
                    <h3 class="font-display text-base font-bold text-slate-900 pb-3 border-b border-slate-100">
                        Order Summary
                    </h3>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal ({{ $summary['total_items'] }} items)</span>
                            <span class="font-bold text-slate-900">{{ $summary['sub_total_formatted'] }}</span>
                        </div>

                        @if ($summary['discount_total'] > 0)
                            <div class="flex justify-between text-blue-700 font-semibold">
                                <span>Promotional Discount</span>
                                <span>-{{ $summary['discount_total_formatted'] }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span>Tracked UK Dispatch</span>
                            <span class="font-bold text-emerald-600">FREE</span>
                        </div>

                        <div class="flex justify-between text-slate-400 text-[11px]">
                            <span>Warranty Registration</span>
                            <span class="text-blue-600 font-semibold">INCLUDED</span>
                        </div>

                        <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                            <span class="font-display text-base font-bold text-slate-900">Estimated Total</span>
                            <span class="font-display text-2xl font-black text-blue-600">{{ $summary['total_formatted'] }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full rounded-full bg-blue-600 hover:bg-blue-700 py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-white shadow transition active:scale-95 flex items-center justify-center gap-2">
                        <span>Checkout Securely</span>
                        <span>&rarr;</span>
                    </a>

                    <div class="text-center pt-1">
                        <a href="{{ route('products.index') }}" class="text-xs text-slate-500 hover:text-blue-600 transition">
                            &larr; Continue Exploring Electronics
                        </a>
                    </div>
                </div>

            </div>

        </div>
    @else
        <!-- Empty Bag -->
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-16 text-center max-w-md mx-auto space-y-4">
            <span class="text-4xl block">⚡</span>
            <h2 class="font-display text-xl font-bold text-slate-900">Your hardware bag is empty</h2>
            <p class="text-xs text-slate-500 leading-relaxed font-light">
                Discover certified Lenovo audio systems, AOC Hybrid ANC headphones, and smart electronics with UK express delivery and wholesale master carton discounts.
            </p>
            <div class="pt-2">
                <a href="{{ route('products.index') }}" class="inline-block rounded-full bg-blue-600 px-6 py-2.5 text-xs font-bold text-white hover:bg-blue-700 transition">
                    Explore Electronics Catalog &rarr;
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
