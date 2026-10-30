@extends('layouts.app')

@section('title', 'All Electronics & Hardware - Nexora Global')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs font-semibold text-slate-400 mb-6 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-bold">All Electronics</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left Filter Sidebar -->
        <aside class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    Categories
                </h3>
                <ul class="space-y-1.5 text-xs font-semibold">
                    <li>
                        <a href="{{ route('products.index') }}" class="block rounded-lg px-2.5 py-1.5 transition {{ !request('category') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            All Categories
                        </a>
                    </li>
                    @foreach ($collections as $collection)
                        @php $slug = $collection->urls->first()?->slug; @endphp
                        <li>
                            <a href="{{ route('products.index', ['category' => $slug]) }}" class="block rounded-lg px-2.5 py-1.5 transition {{ request('category') === $slug ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
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
                <a href="{{ route('experience.retail') }}" class="flex items-center justify-between text-xs font-bold text-slate-700 hover:text-indigo-600">
                    <span>🛍️ Retail Showcase</span>
                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded">1+ Pcs</span>
                </a>
                <a href="{{ route('experience.bulk') }}" class="flex items-center justify-between text-xs font-bold text-slate-700 hover:text-amber-600">
                    <span>📦 Wholesale Bulk Portal</span>
                    <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">Carton Savings</span>
                </a>
            </div>

            <!-- Assurances Card -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 space-y-2 text-xs">
                <span class="font-black text-slate-900 uppercase tracking-wider block">Global Compliance</span>
                <p class="text-slate-500 leading-relaxed">
                    All products are guaranteed authentic Lenovo and AOC models with valid CE and RoHS compliance certifications.
                </p>
            </div>
        </aside>

        <!-- Right Products Grid -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Filter Bar / Sorting -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-xs font-semibold text-slate-500">
                    Showing <strong class="text-slate-900">{{ $products->total() }}</strong> electronic products
                </div>

                <form method="GET" class="flex items-center gap-3">
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <label for="sort" class="text-xs font-bold text-slate-600">Sort by:</label>
                    <select id="sort" name="sort" onchange="this.form.submit()" class="rounded-xl border border-slate-200 py-1.5 px-3 text-xs font-semibold text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest Releases</option>
                        <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200">
                        <p class="text-slate-500 text-sm font-semibold">No electronics found matching your criteria.</p>
                        <a href="{{ route('products.index') }}" class="mt-2 inline-block text-xs font-bold text-indigo-600 underline">Reset all filters</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>

        </div>

    </div>

</div>
@endsection
