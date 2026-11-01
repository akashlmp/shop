@extends('layouts.app')

@section('title', $collection->translateAttribute('name') . ' - A&C Electronics')
@section('meta_description', 'Shop ' . $collection->translateAttribute('name') . ' with A&C Electronics. OEM verified hardware, tiered carton pricing, and fast UK logistics.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-blue-600 transition">Hardware Catalog</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">{{ $collection->translateAttribute('name') }}</span>
    </nav>

    <!-- Category Header Banner -->
    <div class="rounded-3xl bg-slate-900 text-white border border-slate-800 p-8 sm:p-12 text-center max-w-4xl mx-auto space-y-3 relative overflow-hidden shadow-lg">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="relative">
            <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-400">Hardware Collection</span>
            <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-white mt-1">
                {{ $collection->translateAttribute('name') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed font-light mt-2">
                {{ $collection->translateAttribute('description') ?? 'Certified electronic hardware tested to CE, RoHS, and UKCA standards. Wholesale master carton allocations and single-unit retail availability.' }}
            </p>
        </div>
    </div>

    <!-- Filter & Sort Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-2xs">
        <span class="text-xs font-medium text-slate-500">
            Showing <strong class="text-slate-900">{{ $products->total() }}</strong> verified models
        </span>

        <form method="GET" class="flex items-center gap-3">
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
    @if ($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-10 flex justify-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-16 text-center space-y-3">
            <p class="font-display text-base text-slate-800 font-semibold">No models currently available in this category.</p>
            <a href="{{ route('products.index') }}" class="inline-block text-xs font-bold text-blue-600 underline">
                Browse Full Hardware Catalog
            </a>
        </div>
    @endif

</div>
@endsection
