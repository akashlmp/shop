@extends('layouts.app')

@section('title', 'B2B Bulk Wholesale & Master Carton Export - Nexora Global')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Bulk Wholesale Header -->
    <div class="rounded-3xl border border-amber-300/80 bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 border-b-4 border-b-amber-400">
        <div class="space-y-3 max-w-2xl">
            <span class="inline-block rounded-md bg-amber-400 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-950">
                📦 Official B2B Wholesale &amp; Master Carton Portal
            </span>
            <h1 class="text-2xl sm:text-4xl font-black text-white">Commercial Hardware &amp; Volume Pricing</h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                Source Lenovo and AOC certified electronic devices in master carton export packaging. Benefit from automated volume tiers starting at 5 units with maximum margin breaks at 50+ and 100+ units.
            </p>
        </div>

        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-4 text-white text-xs space-y-2 shrink-0 max-w-xs shadow">
            <div class="font-extrabold text-amber-400 flex items-center gap-1.5">
                <span>✈️</span> Wholesale Air &amp; Pallet Freight:
            </div>
            <div class="text-[11px] text-slate-300">&bull; Orders €250+: <strong class="text-white">FREE B2B Pallet Cargo</strong></div>
            <div class="text-[11px] text-slate-300">&bull; Reinforced 5-ply export master carton boxes</div>
            <div class="text-[11px] text-slate-300">&bull; Commercial invoices &amp; CE/RoHS compliance provided</div>
        </div>
    </div>

    <!-- Category Pill Filters -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 text-xs font-bold no-scrollbar">
        <a href="{{ route('experience.bulk') }}" class="rounded-xl px-4 py-2 transition {{ !request('category') ? 'bg-amber-400 text-slate-950 font-black shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-amber-400' }}">
            All Wholesale Deals
        </a>
        @foreach ($collections as $col)
            @php $slug = $col->urls->first()?->slug; @endphp
            <a href="{{ route('experience.bulk', ['category' => $slug]) }}" class="rounded-xl px-4 py-2 transition shrink-0 {{ request('category') === $slug ? 'bg-amber-400 text-slate-950 font-black shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-amber-400' }}">
                {{ $col->translateAttribute('name') }}
            </a>
        @endforeach
    </div>

    <!-- Bulk Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($products as $product)
            <x-bulk-product-card :product="$product" />
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 text-sm font-semibold">No wholesale models found in this category.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
@endsection
