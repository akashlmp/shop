@extends('layouts.app')

@section('title', 'Order #' . $order->reference . ' Details - Lunar Store')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-slate-900">Order #{{ $order->reference }}</h1>
                <span class="rounded bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-2 py-0.5">
                    {{ strtoupper($order->status) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('account.orders') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Back to Order History
        </a>
    </div>

    <!-- 4-Stage Status Tracker -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4 pb-2 border-b border-slate-100">
            Fulfillment Progress
        </h3>
        <div class="grid grid-cols-4 gap-2 text-center text-xs">
            <div class="space-y-1">
                <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs">✓</div>
                <div class="font-extrabold text-emerald-800 text-[11px]">Placed</div>
            </div>
            <div class="space-y-1">
                <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full {{ in_array($order->status, ['processing', 'payment-received', 'dispatched', 'delivered']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} font-bold text-xs">✓</div>
                <div class="font-bold text-slate-700 text-[11px]">Processing</div>
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

    <!-- Items and Details -->
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 font-bold text-sm text-slate-900">
            Order Items ({{ $order->lines->count() }})
        </div>

        <div class="divide-y divide-slate-100 text-xs">
            @foreach ($order->lines as $line)
                <div class="p-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <div class="font-bold text-slate-900">{{ $line->description }}</div>
                        <div class="text-slate-500 text-[11px]">Qty: {{ $line->quantity }} &bull; {{ $line->unitPrice->formatted }}</div>
                    </div>
                    <div class="font-black text-slate-900">
                        {{ $line->subTotal->formatted }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-slate-50 p-5 border-t border-slate-100 space-y-1.5 text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal</span>
                <span class="font-bold text-slate-800">{{ $order->subTotal->formatted }}</span>
            </div>
            @if ($order->discountTotal && $order->discountTotal->value > 0)
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Discount</span>
                    <span>-{{ $order->discountTotal->formatted }}</span>
                </div>
            @endif
            <div class="flex justify-between text-slate-600">
                <span>Shipping</span>
                <span>{{ $order->shippingTotal && $order->shippingTotal->value > 0 ? $order->shippingTotal->formatted : 'FREE' }}</span>
            </div>
            <div class="border-t border-slate-200 pt-2 flex justify-between items-baseline font-black text-slate-900 text-base">
                <span>Total Amount</span>
                <span class="text-emerald-700 text-xl">{{ $order->total->formatted }}</span>
            </div>
        </div>
    </div>

    <!-- Shipping Address & Transaction -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
        @if ($addr = $order->shippingAddress)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-2">
                <h4 class="font-black uppercase tracking-wider text-slate-900 pb-1 border-b border-slate-100">Delivery Address</h4>
                <div class="font-bold text-slate-800">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                <div class="text-slate-600">{{ $addr->line_one }}</div>
                @if ($addr->line_two) <div class="text-slate-600">{{ $addr->line_two }}</div> @endif
                <div class="text-slate-600">{{ $addr->city }}, {{ $addr->state }} - {{ $addr->postcode }}</div>
                <div class="text-slate-500 pt-1">Phone: {{ $addr->contact_phone }}</div>
            </div>
        @endif

        @php $tx = $order->transactions->first(); @endphp
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-2">
            <h4 class="font-black uppercase tracking-wider text-slate-900 pb-1 border-b border-slate-100">Payment Information</h4>
            <div class="flex justify-between">
                <span class="text-slate-500">Gateway:</span>
                <span class="font-bold text-slate-800 uppercase">{{ $tx ? $tx->driver : 'COD' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status:</span>
                <span class="font-bold text-emerald-700">{{ $tx ? ucfirst($tx->status) : 'Pending Collection' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Reference:</span>
                <span class="font-mono text-slate-700">{{ $tx?->reference ?? 'COD' }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
