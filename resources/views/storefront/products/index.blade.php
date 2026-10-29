@extends('layouts.app')

@section('title', 'All Products - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs font-semibold text-slate-400 mb-6 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-bold">All Products</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left Filter Sidebar -->
        <aside class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    Categories
                </h3>
                <ul class="space-y-2 text-xs font-semibold">
                    <li>
                        <a href="{{ route('products.index') }}" class="block rounded-lg px-2.5 py-1.5 transition {{ !request('category') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            All Categories
                        </a>
                    </li>
                    @foreach ($collections as $collection)
                        @php $slug = $collection->urls->first()?->slug; @endphp
                        <li>
                            <a href="{{ route('products.index', ['category' => $slug]) }}" class="block rounded-lg px-2.5 py-1.5 transition {{ request('category') === $slug ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                {{ $collection->translateAttribute('name') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Shopping Experience Links -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-3">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
                    Shopping Mode
                </h3>
                <a href="{{ route('experience.retail') }}" class="flex items-center justify-between text-xs font-bold text-slate-700 hover:text-emerald-600">
                    <span>🛒 Retail Grocery</span>
                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded">Standard</span>
                </a>
                <a href="{{ route('experience.bulk') }}" class="flex items-center justify-between text-xs font-bold text-slate-700 hover:text-amber-600">
                    <span>📦 Wholesale Bulk</span>
                    <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">Savings</span>
                </a>
                <a href="{{ route('experience.quick') }}" class="flex items-center justify-between text-xs font-bold text-slate-700 hover:text-indigo-600">
                    <span>⚡ Quick Shop</span>
                    <span class="text-[10px] bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded">15 Mins</span>
                </a>
            </div>
        </aside>

        <!-- Right Products Grid -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Filter Bar / Sorting -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-xs font-semibold text-slate-500">
                    Showing <strong class="text-slate-900">{{ $products->total() }}</strong> authentic groceries
                </div>

                <form method="GET" class="flex items-center gap-3">
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <label for="sort" class="text-xs font-bold text-slate-600">Sort by:</label>
                    <select id="sort" name="sort" onchange="this.form.submit()" class="rounded-xl border border-slate-200 py-1.5 px-3 text-xs font-semibold text-slate-800 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest Harvest</option>
                        <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            <!-- Grid -->
            @if ($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl">
                        🔍
                    </div>
                    <h3 class="mt-4 text-sm font-bold text-slate-900">No products matched your criteria</h3>
                    <p class="mt-1 text-xs text-slate-500">Try adjusting your filters or search keywords.</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700">
                        Reset Filters
                    </a>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
