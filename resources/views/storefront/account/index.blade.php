@extends('layouts.app')

@section('title', 'Client Dashboard - A&C Electronics')
@section('meta_description', 'Customer and trade account dashboard for A&C Electronics.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-950">Trade &amp; Client Dashboard</h1>
            <p class="text-xs text-slate-500 mt-1">Logged in as: <strong class="text-slate-900">{{ $user->name }}</strong> ({{ $user->email }})</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="rounded-full border border-slate-300 bg-white hover:bg-slate-50 px-5 py-2 text-xs font-semibold text-slate-700 transition">
                Sign Out
            </button>
        </form>
    </div>

    <!-- Navigation Sub-Menu -->
    <div class="flex items-center space-x-6 border-b border-slate-200 pb-2 text-xs font-bold uppercase tracking-wider">
        <a href="{{ route('account.index') }}" class="text-blue-600 border-b-2 border-blue-600 pb-2">Overview</a>
        <a href="{{ route('account.orders') }}" class="text-slate-500 hover:text-slate-900 transition pb-2">Consignments &amp; Orders</a>
        <a href="{{ route('account.addresses') }}" class="text-slate-500 hover:text-slate-900 transition pb-2">Delivery Hubs</a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Consignments</span>
            <div class="font-display text-3xl font-extrabold text-slate-900">{{ $orders->count() }}</div>
            <a href="{{ route('account.orders') }}" class="text-xs font-bold text-blue-600 hover:underline inline-block pt-1">View consignment history &rarr;</a>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Registered Addresses</span>
            <div class="font-display text-3xl font-extrabold text-slate-900">{{ $addresses->count() }}</div>
            <a href="{{ route('account.addresses') }}" class="text-xs font-bold text-blue-600 hover:underline inline-block pt-1">Manage delivery locations &rarr;</a>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Trade Partner Tier</span>
            <div class="font-display text-3xl font-extrabold text-blue-600">Verified Trade</div>
            <span class="text-xs text-slate-400">Direct OEM allocation &bull; 1-Yr UK warranty</span>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="rounded-3xl border border-slate-200 bg-white shadow-2xs overflow-hidden space-y-4 p-6 sm:p-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h2 class="font-display text-lg font-bold text-slate-900">Recent Hardware Orders</h2>
            <a href="{{ route('account.orders') }}" class="text-xs font-bold text-blue-600 hover:underline">View All</a>
        </div>

        @if ($orders->count() > 0)
            <div class="divide-y divide-slate-100 text-xs">
                @foreach ($orders as $ord)
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="font-bold text-slate-900 text-sm">#{{ $ord->reference }}</div>
                            <div class="text-slate-400 text-[11px]">{{ $ord->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="rounded-full bg-blue-100 text-blue-900 text-[10px] font-bold uppercase px-2.5 py-0.5">
                                {{ strtoupper($ord->status) }}
                            </span>
                            <span class="font-display font-bold text-slate-900 text-sm">{{ $ord->total->formatted }}</span>
                            <a href="{{ route('account.orders.show', $ord->reference) }}" class="rounded-full bg-slate-100 hover:bg-slate-200 px-4 py-1.5 font-semibold text-slate-800 transition">
                                Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-500 py-6 text-center">You have not placed any hardware orders yet.</p>
        @endif
    </div>

</div>
@endsection
