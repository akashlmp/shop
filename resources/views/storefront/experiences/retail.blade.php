@extends('layouts.app')

@section('title', 'Retail Electronics Store - A&C Electronics')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white shadow-lg border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <span class="inline-block rounded-md bg-indigo-500/20 border border-indigo-500/40 px-3 py-1 text-xs font-black uppercase tracking-wider text-indigo-300">
                🛍️ Retail Electronics Showcase
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Personal Consumer Audio &amp; Peripherals</h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                Purchase single or small quantities of authentic Lenovo and AOC earbuds, ANC headphones, smartwatches, and optical mice. Free tracked delivery on orders €50+.
            </p>
        </div>
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-4 text-xs text-white space-y-1.5 shrink-0">
            <div class="font-bold text-amber-400">💡 Wholesale Reseller?</div>
            <div class="text-slate-400">Buying 5+ units or master cartons for resale?</div>
            <a href="{{ route('experience.bulk') }}" class="font-bold underline text-amber-300 hover:text-white transition">
                Switch to Bulk Wholesale Portal &rarr;
            </a>
        </div>
    </div>

    <!-- Category Pill Filter Tabs -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 text-xs font-bold no-scrollbar">
        <a href="{{ route('experience.retail') }}" class="rounded-xl px-4 py-2 transition {{ !request('category') ? 'bg-indigo-600 text-white shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-indigo-400' }}">
            All Retail
        </a>
        @foreach ($collections as $col)
            @php $slug = $col->urls->first()?->slug; @endphp
            <a href="{{ route('experience.retail', ['category' => $slug]) }}" class="rounded-xl px-4 py-2 transition shrink-0 {{ request('category') === $slug ? 'bg-indigo-600 text-white shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-indigo-400' }}">
                {{ $col->translateAttribute('name') }}
            </a>
        @endforeach
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-span-4 text-center py-12 bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 text-sm font-semibold">No products found in this category.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
@endsection
