@extends('layouts.app')

@section('title', 'Electronic Hardware Catalog - A&C Electronics')
@section('meta_description', 'Explore our certified electronics catalog: Lenovo EA Series TWS audio, AOC Hybrid ANC headphones, smartwatches, and computer peripherals. Direct OEM sourcing and master carton wholesale.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Electronics Catalog</span>
    </nav>

    <!-- Collection Header Banner -->
    <div class="rounded-3xl bg-slate-900 text-white border border-slate-800 p-8 sm:p-12 text-center max-w-4xl mx-auto space-y-3 shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="relative">
            <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-400">Direct OEM Hardware</span>
            <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-white mt-1">
                A&amp;C Electronic Hardware Catalog
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed font-light mt-2">
                Certified Lenovo &amp; AOC audio systems, smart biometric wearables, and computer peripherals. Available for single-unit retail dispatch and tiered master carton wholesale.
            </p>
        </div>
    </div>

    <!-- Category Pills Strip -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2">
        <a href="{{ route('products.index') }}"
           class="rounded-full px-4 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ !request('category') ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:border-slate-400' }}">
            All Hardware ({{ $products->total() }})
        </a>
        @foreach ($collections as $collection)
            @php $slug = $collection->urls->first()?->slug; @endphp
            <a href="{{ route('products.index', ['category' => $slug]) }}"
               class="rounded-full px-4 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ request('category') === $slug ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:border-blue-400' }}">
                {{ $collection->translateAttribute('name') }}
            </a>
        @endforeach
    </div>

    <!-- Filter & Sort Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-2xs">
        <div class="text-xs font-medium text-slate-500">
            Showing <strong class="text-slate-900">{{ $products->count() }}</strong> of <strong class="text-slate-900">{{ $products->total() }}</strong> verified models
        </div>

        <form method="GET" class="flex items-center gap-3">
            @if (request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if (request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif

            <label for="sort" class="text-xs font-semibold text-slate-600">Sort by:</label>
            <select id="sort"
                    name="sort"
                    onchange="this.form.submit()"
                    class="rounded-full border border-slate-200 bg-slate-50 py-1.5 px-3.5 text-xs font-semibold text-slate-800 focus:border-blue-600 focus:ring-blue-600">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest Releases</option>
                <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </form>
    </div>

    <!-- Products Grid (2-Column Mobile, 4-Column Desktop) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200 space-y-3">
                <span class="text-3xl block">🔍</span>
                <p class="font-display text-base text-slate-800 font-semibold">No hardware models found matching your selection.</p>
                <a href="{{ route('products.index') }}" class="inline-block text-xs font-bold text-blue-600 underline">
                    Clear all filters
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10 flex justify-center">
        {{ $products->links() }}
    </div>

</div>
@endsection
