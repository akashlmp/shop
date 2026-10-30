@extends('layouts.app')

@section('title', 'Bulk Buy & Wholesale - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Bulk Wholesale Header -->
    <div class="rounded-3xl border-2 border-amber-300 bg-gradient-to-r from-amber-500 via-amber-600 to-orange-600 p-6 sm:p-10 text-slate-950 shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-2xl">
            <span class="inline-block rounded-md bg-slate-950 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-amber-400">
                📦 Wholesale Bulk Buy Hub
            </span>
            <h1 class="text-2xl sm:text-4xl font-black text-slate-950">Volume Savings for Homes, Caterers &amp; Stores</h1>
            <p class="text-xs sm:text-sm font-semibold text-slate-900 leading-relaxed">
                Save up to 25% with automatic volume pricing tiers. Minimum order quantities apply. Packaged in protective wholesale cartons, sacks, and master shippers.
            </p>
        </div>

        <div class="rounded-2xl bg-slate-950 p-4 text-white text-xs space-y-1.5 shrink-0 max-w-xs shadow">
            <div class="font-extrabold text-amber-400">🚚 Wholesale Freight Rules:</div>
            <div class="text-[11px] text-slate-300">&bull; Orders €100+: FREE Commercial Freight</div>
            <div class="text-[11px] text-slate-300">&bull; Automatic price tier drops at 5+ &amp; 10+ units</div>
        </div>
    </div>

    <!-- Category Pill Filters -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 text-xs font-bold no-scrollbar">
        <a href="{{ route('experience.bulk') }}" class="rounded-xl px-4 py-2 transition {{ !request('category') ? 'bg-amber-500 text-slate-950 font-black shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-amber-400' }}">
            All Wholesale Deals
        </a>
        @foreach ($collections as $col)
            @php $slug = $col->urls->first()?->slug; @endphp
            <a href="{{ route('experience.bulk', ['category' => $slug]) }}" class="rounded-xl px-4 py-2 transition shrink-0 {{ request('category') === $slug ? 'bg-amber-500 text-slate-950 font-black shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-amber-400' }}">
                {{ $col->translateAttribute('name') }}
            </a>
        @endforeach
    </div>

    <!-- Bulk Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($products as $product)
            <x-bulk-product-card :product="$product" />
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
@endsection

