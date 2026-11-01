@extends('layouts.app')

@section('title', 'Consignment #' . $order->reference . ' Details - A&C Electronics')
@section('meta_description', 'Detailed consignment tracking for order #' . $order->reference . ' at A&C Electronics.')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-display text-2xl font-bold text-slate-900">Consignment #{{ $order->reference }}</h1>
                <span class="rounded-full bg-blue-100 text-blue-900 text-[10px] font-bold uppercase px-2.5 py-0.5">
                    {{ strtoupper($order->status) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('account.orders') }}" class="text-xs font-bold text-blue-600 hover:text-slate-900">
            &larr; Back to Consignment History
        </a>
    </div>

    <!-- 4-Stage Status Tracker -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 pb-2 border-b border-slate-100">
            Consignment Logistics Tracking
        </h3>
        <div class="grid grid-cols-4 gap-2 text-center text-xs">
            <div class="space-y-1">
                <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-white font-bold text-xs">✓</div>
                <div class="font-bold text-slate-900 text-[10px]">Received</div>
            </div>
            <div class="space-y-1">
                <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full {{ in_array($order->status, ['processing', 'payment-received', 'dispatched', 'shipped', 'delivered']) ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400' }} font-bold text-xs">⚡</div>
                <div class="font-bold text-slate-700 text-[10px]">Batch Quality Check</div>
            </div>
            <div class="space-y-1">
                <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full {{ in_array($order->status, ['dispatched', 'shipped', 'delivered']) ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400' }} font-bold text-xs">3</div>
                <div class="font-bold text-slate-500 text-[10px]">Dispatched via Courier</div>
            </div>
            <div class="space-y-1">
                <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full {{ $order->status === 'delivered' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400' }} font-bold text-xs">4</div>
                <div class="font-bold text-slate-500 text-[10px]">Delivered</div>
            </div>
        </div>
    </div>

    <!-- Items and Details -->
    <div class="rounded-3xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 font-display font-bold text-base text-slate-900">
            Consignment Hardware Items ({{ $order->productLines->count() }})
        </div>

        <div class="divide-y divide-slate-100 text-xs">
            @foreach ($order->productLines as $line)
                <div class="p-4 sm:p-5 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <div class="font-bold text-slate-900 text-sm">{{ $line->description }}</div>
                        <div class="text-slate-400 text-[11px]">Qty: {{ $line->quantity }} &bull; {{ $line->unit_price->formatted }}</div>
                    </div>
                    <div class="font-display font-bold text-slate-900">
                        {{ $line->sub_total->formatted }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-slate-50 p-5 border-t border-slate-100 space-y-1.5 text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal</span>
                <span class="font-bold text-slate-800">{{ $order->sub_total->formatted }}</span>
            </div>
            @if ($order->discount_total && $order->discount_total->value > 0)
                <div class="flex justify-between text-blue-700 font-semibold">
                    <span>Discount</span>
                    <span>-{{ $order->discount_total->formatted }}</span>
                </div>
            @endif
            <div class="flex justify-between text-slate-600">
                <span>Freight Shipping</span>
                <span>{{ $order->shipping_total && $order->shipping_total->value > 0 ? $order->shipping_total->formatted : 'FREE' }}</span>
            </div>
            <div class="border-t border-slate-200 pt-2 flex justify-between items-baseline font-display font-bold text-slate-900 text-base">
                <span>Total Amount</span>
                <span class="font-display text-xl font-black text-blue-600">{{ $order->total->formatted }}</span>
            </div>
        </div>
    </div>

    <!-- Shipping Address & Transaction -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
        @if ($addr = $order->shippingAddress)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-2">
                <h4 class="font-display font-bold text-slate-900 pb-2 border-b border-slate-100">Delivery Destination</h4>
                <div class="font-bold text-slate-800">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                <div class="text-slate-600">{{ $addr->line_one }}</div>
                @if ($addr->line_two) <div class="text-slate-600">{{ $addr->line_two }}</div> @endif
                <div class="text-slate-600">{{ $addr->city }}, {{ $addr->state }} - {{ $addr->postcode }}</div>
                <div class="text-slate-400 pt-1">Phone: {{ $addr->contact_phone }}</div>
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-2">
            <h4 class="font-display font-bold text-slate-900 pb-2 border-b border-slate-100">Payment &amp; Trade Settlement</h4>
            @php $tx = $order->transactions->first(); @endphp
            <div class="text-slate-600 space-y-1">
                <div>Method: <strong class="text-slate-800 uppercase">{{ $tx ? $tx->driver : 'Cash / Wire on Delivery' }}</strong></div>
                <div>Status: <span class="font-bold text-emerald-700">{{ strtoupper($tx?->status ?? 'PENDING') }}</span></div>
                @if ($tx && $tx->reference)
                    <div class="font-mono text-[11px] text-slate-400">Ref: {{ $tx->reference }}</div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
