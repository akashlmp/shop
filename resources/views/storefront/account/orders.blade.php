@extends('layouts.app')

@section('title', 'Consignment History - A&C Electronics')
@section('meta_description', 'View your order and consignment history with A&C Electronics.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-950">Consignment &amp; Order History</h1>
            <p class="text-xs text-slate-500 mt-1">Track dispatch progress, view commercial receipts, and re-order master cartons</p>
        </div>
        <a href="{{ route('account.index') }}" class="text-xs font-bold text-blue-600 hover:text-slate-900">
            &larr; Back to Dashboard
        </a>
    </div>

    @if ($orders->count() > 0)
        <div class="rounded-3xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
            <div class="divide-y divide-slate-100 text-xs">
                @foreach ($orders as $order)
                    <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 text-base">#{{ $order->reference }}</span>
                                <span class="rounded-full bg-blue-100 text-blue-900 text-[10px] font-bold uppercase px-2.5 py-0.5">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-400">
                                Placed on {{ $order->created_at->format('M d, Y h:i A') }} &bull; {{ $order->lines->count() }} items
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-6">
                            <div class="text-right">
                                <div class="text-[10px] uppercase text-slate-400 font-bold">Total</div>
                                <div class="font-display text-lg font-black text-slate-900">{{ $order->total->formatted }}</div>
                            </div>

                            <a href="{{ route('account.orders.show', $order->reference) }}" class="rounded-full bg-blue-600 text-white hover:bg-blue-700 px-5 py-2 font-bold text-xs transition">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $orders->links() }}
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-16 text-center space-y-3">
            <span class="text-4xl block">📦</span>
            <h3 class="font-display text-base font-bold text-slate-900">No consignments placed yet</h3>
            <p class="text-xs text-slate-500">When you place an order, its real-time processing and tracked freight delivery will appear here.</p>
            <a href="{{ route('products.index') }}" class="inline-block rounded-full bg-blue-600 px-6 py-2.5 text-xs font-bold text-white hover:bg-blue-700 transition">
                Browse Electronics Catalog &rarr;
            </a>
        </div>
    @endif

</div>
@endsection
