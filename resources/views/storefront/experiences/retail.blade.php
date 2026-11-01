@extends('layouts.app')

@section('title', 'Retail Consumer Store - A&C Electronics')
@section('meta_description', 'Purchase individual certified electronic devices from Lenovo & AOC with next-day UK dispatch and full 1-year warranty.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-slate-900 text-white border border-slate-800 p-8 sm:p-12 text-center max-w-4xl mx-auto space-y-3 relative overflow-hidden shadow-lg">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="relative">
            <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-400">
                Retail Consumer Electronics
            </span>
            <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-white mt-1">
                Consumer Electronics Storefront
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed font-light mt-2">
                Order single units of Lenovo EA audio series, AOC Hybrid ANC headphones, AMOLED smartwatches, and ergonomic computer mice with 24-48h tracked UK delivery.
            </p>
        </div>
    </div>

    <!-- Category Pill Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-semibold no-scrollbar">
        <a href="{{ route('experience.retail') }}" class="rounded-full px-4 py-2 transition {{ !request('category') ? 'bg-blue-600 text-white shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-slate-400' }}">
            All Hardware
        </a>
        @foreach ($collections as $col)
            @php $slug = $col->urls->first()?->slug; @endphp
            <a href="{{ route('experience.retail', ['category' => $slug]) }}" class="rounded-full px-4 py-2 transition shrink-0 {{ request('category') === $slug ? 'bg-blue-600 text-white shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-blue-400' }}">
                {{ $col->translateAttribute('name') }}
            </a>
        @endforeach
    </div>

    <!-- Products Grid (2-Column Mobile, 4-Column Desktop) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-slate-200">
                <p class="text-slate-500 text-sm font-light">No hardware found in this category.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $products->links() }}
    </div>

</div>
@endsection
