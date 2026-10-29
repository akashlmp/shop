@extends('layouts.app')

@section('title', 'Shopping Cart - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Your Shopping Cart</h1>
            <p class="text-xs text-slate-500 mt-1">Review your items, apply promotional coupons, and proceed to checkout</p>
        </div>
        @if ($summary['total_items'] > 0)
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure you want to clear your cart?')" class="text-xs font-bold text-rose-600 hover:text-rose-800 transition">
                    Clear Entire Cart
                </button>
            </form>
        @endif
    </div>

    @if ($summary['total_items'] > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Cart Line Items Table -->
            <div class="lg:col-span-8 space-y-4">
                
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="divide-y divide-slate-100">
                        @foreach ($summary['lines'] as $line)
                            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                
                                <!-- Product Info & Image -->
                                <div class="flex items-center gap-4">
                                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-slate-50 border border-slate-100">
                                        <img src="{{ $line['image_url'] }}" alt="{{ $line['name'] }}" class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="space-y-1">
                                        @if ($line['is_bulk'])
                                            <span class="inline-block rounded bg-amber-100 text-amber-800 text-[9px] font-black uppercase px-1.5 py-0.5">
                                                📦 Wholesale Bulk Pack
                                            </span>
                                        @endif
                                        <h3 class="text-sm font-bold text-slate-900 leading-tight">
                                            {{ $line['name'] }}
                                        </h3>
                                        <div class="text-xs text-slate-500">
                                            Unit: {{ ucfirst($line['unit_of_measure']) }} &bull;
                                            <span class="font-bold text-slate-700">{{ $line['unit_price_formatted'] }}</span> each
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Controls & Line Total -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                                    
                                    <!-- Quantity Update Form -->
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                                        @csrf
                                        <input type="hidden" name="line_id" value="{{ $line['id'] }}">
                                        
                                        <button type="submit" name="quantity" value="{{ $line['quantity'] - 1 }}" class="h-7 w-7 rounded-lg bg-white text-slate-700 font-extrabold text-xs shadow-xs hover:bg-slate-200 flex items-center justify-center">
                                            -
                                        </button>
                                        <span class="w-10 text-center text-xs font-black text-slate-900">
                                            {{ $line['quantity'] }}
                                        </span>
                                        <button type="submit" name="quantity" value="{{ $line['quantity'] + 1 }}" class="h-7 w-7 rounded-lg bg-white text-slate-700 font-extrabold text-xs shadow-xs hover:bg-slate-200 flex items-center justify-center">
                                            +
                                        </button>
                                    </form>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <div class="text-sm font-black text-slate-900">
                                            {{ $line['sub_total_formatted'] }}
                                        </div>
                                    </div>

                                    <!-- Remove Item -->
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="line_id" value="{{ $line['id'] }}">
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 transition p-1" title="Remove">
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

                <!-- Express Delivery Pincode Check Box in Cart -->
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" x-data="{
                    postcode: '{{ session('quick_postcode', '') }}',
                    checkResult: null,
                    isChecking: false,
                    checkService() {
                        if(!this.postcode) return;
                        this.isChecking = true;
                        fetch('{{ route('cart.check-serviceability') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ postcode: this.postcode })
                        })
                        .then(r => r.json())
                        .then(d => {
                            this.isChecking = false;
                            this.checkResult = d;
                        });
                    }
                }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                <span>⚡ Delivery Estimation by Postal Code</span>
                            </h4>
                            <p class="text-[11px] text-slate-500">Check if your address qualifies for 15-Minute Instant Express Delivery</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="text" x-model="postcode" placeholder="Postal Code (e.g. 400001)" class="rounded-xl border border-slate-300 py-1.5 px-3 text-xs font-bold text-slate-800 focus:ring-emerald-500">
                            <button type="button" @click="checkService()" class="rounded-xl bg-slate-900 text-white px-3 py-1.5 text-xs font-bold hover:bg-slate-800">
                                Check
                            </button>
                        </div>
                    </div>

                    <template x-if="checkResult">
                        <div class="mt-3 p-2.5 rounded-xl text-xs font-bold" :class="checkResult.serviceable ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700'">
                            <span x-text="checkResult.message"></span>
                        </div>
                    </template>
                </div>

            </div>

            <!-- Right: Order Cost Breakdown Summary -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Coupon Box -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">
                        Apply Promo Coupon
                    </h3>

                    @if ($summary['coupon_code'])
                        <div class="flex items-center justify-between rounded-xl bg-emerald-50 p-3 border border-emerald-200">
                            <div class="text-xs font-bold text-emerald-800">
                                Code <span class="font-black font-mono uppercase">{{ $summary['coupon_code'] }}</span> applied!
                            </div>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-extrabold text-rose-600 hover:text-rose-800">
                                    Remove
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="Enter coupon (e.g. WELCOME10, BULK15)" class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-xs font-bold uppercase text-slate-800 focus:border-emerald-500 focus:ring-emerald-500">
                            <button type="submit" class="rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-xs font-extrabold text-white transition active:scale-95 shadow">
                                Apply
                            </button>
                        </form>
                        <div class="text-[10px] text-slate-400">
                            Try: <strong class="text-slate-600">WELCOME10</strong> (10% off) or <strong class="text-slate-600">BULK15</strong> (€15 off)
                        </div>
                    @endif
                </div>

                <!-- Totals Box -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
                        Order Summary
                    </h3>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal ({{ $summary['total_items'] }} items)</span>
                            <span class="font-bold text-slate-900">{{ $summary['sub_total_formatted'] }}</span>
                        </div>

                        @if ($summary['discount_total'] > 0)
                            <div class="flex justify-between text-emerald-700 font-bold">
                                <span>Promotional Discount</span>
                                <span>-{{ $summary['discount_total_formatted'] }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span>Estimated Shipping</span>
                            @if ($summary['shipping_total'] > 0)
                                <span class="font-bold text-slate-900">{{ $summary['shipping_total_formatted'] }}</span>
                            @else
                                <span class="font-bold text-emerald-600">FREE</span>
                            @endif
                        </div>

                        <div class="flex justify-between text-slate-500 text-[11px]">
                            <span>Estimated Tax (Included)</span>
                            <span>{{ $summary['tax_total_formatted'] }}</span>
                        </div>

                        <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                            <span class="text-sm font-black text-slate-900">Final Total</span>
                            <span class="text-2xl font-black text-emerald-700">{{ $summary['total_formatted'] }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 py-3.5 px-4 text-sm font-black text-white shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center justify-center gap-2">
                        <span>Proceed to Secure Checkout</span>
                        <span>&rarr;</span>
                    </a>

                    <div class="text-center">
                        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-600 transition">
                            &larr; Continue Shopping
                        </a>
                    </div>
                </div>

            </div>

        </div>
    @else
        <!-- Empty Cart -->
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-16 text-center max-w-lg mx-auto space-y-4">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-3xl">
                🛒
            </div>
            <h2 class="text-xl font-black text-slate-900">Your cart is empty</h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                Discover our fresh fruits, dairy, whole spices, royal basmati rice, and wholesale packs ready for immediate delivery.
            </p>
            <div class="pt-2 flex flex-col sm:flex-row justify-center gap-2">
                <a href="{{ route('products.index') }}" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-emerald-700 shadow">
                    Shop Retail Groceries
                </a>
                <a href="{{ route('experience.bulk') }}" class="rounded-xl bg-amber-400 px-5 py-2.5 text-xs font-bold text-slate-950 hover:bg-amber-500 shadow">
                    Shop Wholesale Bulk
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
