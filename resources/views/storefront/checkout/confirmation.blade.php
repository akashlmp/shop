@extends('layouts.app')

@section('title', 'Order Confirmation #' . $order->reference . ' - Lunar Store')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Success Header -->
    <div class="rounded-3xl border border-emerald-200 bg-white p-6 sm:p-10 shadow-sm text-center space-y-4">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div>
            <span class="rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-3 py-1">
                Order Placed Successfully
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
                Thank you for your order!
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Your order reference is <strong class="text-slate-900 font-mono text-sm">#{{ $order->reference }}</strong>.
                A confirmation has been logged to your account.
            </p>
        </div>

        <!-- 4-Stage Visual Status Tracker -->
        <div class="pt-6 max-w-2xl mx-auto">
            <div class="grid grid-cols-4 gap-2 text-center text-xs">
                <div class="space-y-1">
                    <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs">✓</div>
                    <div class="font-extrabold text-emerald-800 text-[11px]">Placed</div>
                </div>
                <div class="space-y-1">
                    <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full {{ in_array($order->status, ['processing', 'payment-received', 'dispatched', 'delivered']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} font-bold text-xs">✓</div>
                    <div class="font-bold text-slate-700 text-[11px]">Confirmed</div>
                </div>
                <div class="space-y-1">
                    <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full {{ in_array($order->status, ['dispatched', 'delivered']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} font-bold text-xs">3</div>
                    <div class="font-bold text-slate-500 text-[11px]">Dispatched</div>
                </div>
                <div class="space-y-1">
                    <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full {{ $order->status === 'delivered' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} font-bold text-xs">4</div>
                    <div class="font-bold text-slate-500 text-[11px]">Delivered</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details & Shipping Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Delivery Details -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                <span>📍 Shipping Address</span>
            </h3>
            
            @php $addr = $order->shippingAddress; @endphp
            @if ($addr)
                <div class="text-xs text-slate-700 space-y-1 font-medium">
                    <div class="font-bold text-slate-900">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                    <div>{{ $addr->line_one }}</div>
                    @if ($addr->line_two) <div>{{ $addr->line_two }}</div> @endif
                    <div>{{ $addr->city }}, {{ $addr->state }} - <strong class="font-mono text-slate-900">{{ $addr->postcode }}</strong></div>
                    <div class="text-slate-500 pt-1">Phone: {{ $addr->contact_phone }} &bull; Email: {{ $addr->contact_email }}</div>
                    @if ($addr->delivery_instructions)
                        <div class="rounded bg-slate-50 p-2 text-[11px] text-slate-600 border border-slate-100 mt-2">
                            <strong>Note:</strong> {{ $addr->delivery_instructions }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Payment & Status -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                <span>💳 Payment Information</span>
            </h3>

            @php $tx = $order->transactions->first(); @endphp
            <div class="text-xs text-slate-700 space-y-2">
                <div class="flex justify-between items-center">
                    <span>Order Status:</span>
                    <span class="rounded bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-2 py-0.5">
                        {{ strtoupper($order->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Payment Gateway:</span>
                    <span class="font-bold text-slate-900 uppercase">{{ $tx ? $tx->driver : 'COD' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Transaction Reference:</span>
                    <span class="font-mono text-[11px] font-bold text-slate-700">{{ $tx?->reference ?? 'PENDING' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Payment Status:</span>
                    <span class="font-bold {{ $tx && $tx->status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $tx ? ucfirst($tx->status) : 'Pending Collection' }}
                    </span>
                </div>
                <div class="border-t border-slate-100 pt-2 flex justify-between items-baseline">
                    <span class="font-bold text-slate-900">Total Paid:</span>
                    <span class="text-lg font-black text-emerald-700">{{ $order->total->formatted }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Ordered Items Table -->
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 font-bold text-sm text-slate-900">
            Items in this Order ({{ $order->lines->count() }})
        </div>

        <div class="divide-y divide-slate-100 text-xs">
            @foreach ($order->lines as $line)
                <div class="p-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <div class="font-bold text-slate-900">{{ $line->description }}</div>
                        <div class="text-slate-500 text-[11px]">
                            Qty: <strong class="text-slate-700">{{ $line->quantity }}</strong> &bull;
                            Unit: {{ $line->unitPrice->formatted }}
                        </div>
                    </div>
                    <div class="font-black text-slate-900">
                        {{ $line->subTotal->formatted }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Breakdown Footer -->
        <div class="bg-slate-50 p-4 sm:p-5 border-t border-slate-100 space-y-1.5 text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal</span>
                <span class="font-bold text-slate-800">{{ $order->subTotal->formatted }}</span>
            </div>
            @if ($order->discountTotal && $order->discountTotal->value > 0)
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Promotional Discount</span>
                    <span>-{{ $order->discountTotal->formatted }}</span>
                </div>
            @endif
            <div class="flex justify-between text-slate-600">
                <span>Shipping</span>
                <span>{{ $order->shippingTotal && $order->shippingTotal->value > 0 ? $order->shippingTotal->formatted : 'FREE' }}</span>
            </div>
            <div class="flex justify-between text-slate-500 text-[11px]">
                <span>Taxes</span>
                <span>{{ $order->taxTotal->formatted }}</span>
            </div>
            <div class="border-t border-slate-200 pt-2 flex justify-between items-baseline font-black text-slate-900 text-base">
                <span>Final Total</span>
                <span class="text-emerald-700 text-xl">{{ $order->total->formatted }}</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4">
        <a href="{{ route('home') }}" class="w-full sm:w-auto rounded-xl bg-emerald-600 hover:bg-emerald-700 py-3 px-6 text-xs font-bold text-white transition shadow text-center">
            &larr; Return to Storefront
        </a>
        @auth
            <a href="{{ route('account.orders') }}" class="w-full sm:w-auto rounded-xl border border-slate-300 bg-white hover:bg-slate-50 py-3 px-6 text-xs font-bold text-slate-800 transition shadow-xs text-center">
                View in Account History &rarr;
            </a>
        @endauth
    </div>

</div>
@endsection

