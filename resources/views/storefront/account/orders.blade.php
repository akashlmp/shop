@extends('layouts.app')

@section('title', 'Order History - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Your Order History</h1>
            <p class="text-xs text-slate-500 mt-1">Track current fulfillment status, download past invoices, and review order items</p>
        </div>
        <a href="{{ route('account.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Back to Dashboard
        </a>
    </div>

    @if ($orders->count() > 0)
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="divide-y divide-slate-100 text-xs">
                @foreach ($orders as $order)
                    <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm font-black text-slate-900">#{{ $order->reference }}</span>
                                <span class="rounded bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-2 py-0.5">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-500">
                                Placed on {{ $order->created_at->format('M d, Y h:i A') }} &bull; {{ $order->lines->count() }} items
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-6">
                            <div class="text-right">
                                <div class="text-xs text-slate-400">Total Amount</div>
                                <div class="text-base font-black text-emerald-700">{{ $order->total->formatted }}</div>
                            </div>

                            <a href="{{ route('account.orders.show', $order->reference) }}" class="rounded-xl bg-slate-900 text-white hover:bg-slate-800 px-4 py-2 font-bold transition">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center space-y-3">
            <div class="text-3xl">📦</div>
            <h3 class="text-sm font-bold text-slate-900">No orders placed yet</h3>
            <p class="text-xs text-slate-500">When you place an order, its real-time progress will appear here.</p>
            <a href="{{ route('products.index') }}" class="inline-block rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700">
                Browse Products
            </a>
        </div>
    @endif

</div>
@endsection

