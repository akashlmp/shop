@extends('layouts.app')

@section('title', 'My Account - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Customer Dashboard</h1>
            <p class="text-xs text-slate-500 mt-1">Hello, <strong class="text-slate-900">{{ $user->name }}</strong> ({{ $user->email }})</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-bold text-slate-700 transition">
                Sign Out
            </button>
        </form>
    </div>

    <!-- Navigation Sub-Menu -->
    <div class="flex items-center space-x-3 border-b border-slate-200 pb-2 text-xs font-bold">
        <a href="{{ route('account.index') }}" class="text-emerald-700 border-b-2 border-emerald-600 pb-2">Overview</a>
        <a href="{{ route('account.orders') }}" class="text-slate-500 hover:text-slate-800 transition pb-2">Order History</a>
        <a href="{{ route('account.addresses') }}" class="text-slate-500 hover:text-slate-800 transition pb-2">Saved Addresses</a>
    </div>

    <!-- Stats & Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase">Total Orders</span>
            <div class="text-2xl font-black text-slate-900">{{ $orders->count() }}</div>
            <a href="{{ route('account.orders') }}" class="text-xs font-semibold text-emerald-600 hover:underline inline-block pt-1">View all orders &rarr;</a>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase">Saved Addresses</span>
            <div class="text-2xl font-black text-slate-900">{{ $addresses->count() }}</div>
            <a href="{{ route('account.addresses') }}" class="text-xs font-semibold text-emerald-600 hover:underline inline-block pt-1">Manage addresses &rarr;</a>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase">Loyalty Status</span>
            <div class="text-2xl font-black text-emerald-600">Gold Customer</div>
            <span class="text-xs text-slate-400">Eligible for 15-min quick deliveries</span>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden space-y-4 p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-900">Recent Orders</h2>
            <a href="{{ route('account.orders') }}" class="text-xs font-bold text-emerald-600 hover:underline">View All</a>
        </div>

        @if ($orders->count() > 0)
            <div class="divide-y divide-slate-100 text-xs">
                @foreach ($orders as $ord)
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="font-mono font-bold text-slate-900 text-sm">#{{ $ord->reference }}</div>
                            <div class="text-slate-400 text-[11px]">{{ $ord->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="rounded bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-2 py-0.5">
                                {{ strtoupper($ord->status) }}
                            </span>
                            <span class="font-black text-slate-900 text-sm">{{ $ord->total->formatted }}</span>
                            <a href="{{ route('account.orders.show', $ord->reference) }}" class="rounded-lg bg-slate-100 hover:bg-slate-200 px-3 py-1.5 font-bold text-slate-700 transition">
                                Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-500 py-6 text-center">You have not placed any orders yet.</p>
        @endif
    </div>

</div>
@endsection

