@extends('layouts.app')

@section('title', 'Order Confirmation #' . $order->reference . ' - A&C Electronics')
@section('meta_description', 'Your electronic hardware consignment has been confirmed with A&C Electronics.')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Success Header -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-2xs text-center space-y-4">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-700">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        <div>
            <span class="rounded-md bg-blue-100 text-blue-900 text-[10px] font-bold uppercase tracking-wider px-3 py-1">
                Hardware Consignment Confirmed
            </span>
            <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-slate-950 mt-2">
                Thank you for your order!
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Your consignment reference is <strong class="font-display text-blue-600 text-base">#{{ $order->reference }}</strong>.
                Our London logistics hub is now preparing your hardware dispatch.
            </p>
        </div>

        <!-- 4-Stage Visual Status Tracker -->
        <div class="pt-6 max-w-xl mx-auto">
            <div class="grid grid-cols-4 gap-2 text-center text-xs">
                <div class="space-y-1">
                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-white font-bold text-xs">✓</div>
                    <div class="font-bold text-slate-900 text-[10px]">Received</div>
                </div>
                <div class="space-y-1">
                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-blue-500 text-white font-bold text-xs">⚡</div>
                    <div class="font-bold text-blue-700 text-[10px]">Processing</div>
                </div>
                <div class="space-y-1">
                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-400 font-bold text-xs">3</div>
                    <div class="font-medium text-slate-400 text-[10px]">Dispatched</div>
                </div>
                <div class="space-y-1">
                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-400 font-bold text-xs">4</div>
                    <div class="font-medium text-slate-400 text-[10px]">Delivered</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details & Shipping Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Delivery Details -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                <span>📍 Consignee Destination</span>
            </h3>
            
            @php $addr = $order->shippingAddress; @endphp
            @if ($addr)
                <div class="text-xs text-slate-600 space-y-1">
                    <div class="font-bold text-slate-900 text-sm">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                    <div>{{ $addr->line_one }}</div>
                    @if ($addr->line_two) <div>{{ $addr->line_two }}</div> @endif
                    <div>{{ $addr->city }}, {{ $addr->state }} - <strong class="text-slate-900">{{ $addr->postcode }}</strong></div>
                    <div class="text-slate-400 pt-1">Contact: {{ $addr->contact_phone }} &bull; {{ $addr->contact_email }}</div>
                    @if ($addr->delivery_instructions)
                        <div class="rounded-xl bg-slate-50 p-2.5 text-[11px] text-slate-600 border border-slate-100 mt-2">
                            <strong>Note:</strong> {{ $addr->delivery_instructions }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Payment & Status -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                <span>💳 Order &amp; Payment Status</span>
            </h3>

            @php $tx = $order->transactions->first(); @endphp
            <div class="text-xs text-slate-600 space-y-2">
                <div class="flex justify-between items-center">
                    <span>Consignment Status:</span>
                    <span class="rounded-full bg-blue-100 text-blue-900 text-[10px] font-bold uppercase px-2.5 py-0.5">
                        {{ strtoupper($order->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Payment Method:</span>
                    <span class="font-semibold text-slate-900 uppercase">{{ $tx ? $tx->driver : 'Cash / Bank Wire on Delivery' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Transaction Reference:</span>
                    <span class="font-mono text-[11px] font-bold text-slate-700">{{ $tx?->reference ?? 'PENDING' }}</span>
                </div>
                <div class="border-t border-slate-100 pt-2 flex justify-between items-baseline">
                    <span class="font-semibold text-slate-900">Total:</span>
                    <span class="font-display text-lg font-bold text-blue-600">{{ $order->total->formatted }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Ordered Items Table -->
    <div class="rounded-3xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 font-display font-bold text-base text-slate-900">
            Hardware Items in this Consignment ({{ $order->productLines->count() }})
        </div>

        <div class="divide-y divide-slate-100 text-xs">
            @foreach ($order->productLines as $line)
                <div class="p-4 sm:p-5 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <div class="font-bold text-slate-900 text-sm">{{ $line->description }}</div>
                        <div class="text-slate-400 text-[11px]">
                            Qty: <strong class="text-slate-700">{{ $line->quantity }}</strong> &bull;
                            Unit: {{ $line->unit_price->formatted }}
                        </div>
                    </div>
                    <div class="font-display font-bold text-slate-900">
                        {{ $line->sub_total->formatted }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Breakdown Footer -->
        <div class="bg-slate-50 p-5 border-t border-slate-100 space-y-1.5 text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal</span>
                <span class="font-bold text-slate-800">{{ $order->sub_total->formatted }}</span>
            </div>
            @if ($order->discount_total && $order->discount_total->value > 0)
                <div class="flex justify-between text-blue-700 font-semibold">
                    <span>Promotional Discount</span>
                    <span>-{{ $order->discount_total->formatted }}</span>
                </div>
            @endif
            <div class="flex justify-between text-slate-600">
                <span>Tracked UK Dispatch</span>
                <span class="text-emerald-600 font-bold">FREE</span>
            </div>
            <div class="border-t border-slate-200 pt-2 flex justify-between items-baseline font-display font-bold text-slate-900 text-base">
                <span>Total Amount</span>
                <span class="font-display text-xl font-black text-blue-600">{{ $order->total->formatted }}</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4">
        <a href="{{ route('home') }}" class="w-full sm:w-auto rounded-full bg-blue-600 hover:bg-blue-700 py-3 px-8 text-xs font-bold text-white transition text-center shadow">
            &larr; Return to Storefront
        </a>
        @auth
            <a href="{{ route('account.orders') }}" class="w-full sm:w-auto rounded-full border border-slate-300 bg-white hover:bg-slate-50 py-3 px-8 text-xs font-semibold text-slate-800 transition text-center">
                View Account Consignments &rarr;
            </a>
        @endauth
    </div>

</div>
@endsection
