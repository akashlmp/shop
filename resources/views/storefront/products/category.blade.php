@extends('layouts.app')

@section('title', $collection->translateAttribute('name') . ' - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs font-semibold text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-slate-800 transition">Categories</a>
        <span>/</span>
        <span class="text-slate-900 font-bold">{{ $collection->translateAttribute('name') }}</span>
    </nav>

    <!-- Category Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-emerald-800 to-teal-900 p-6 sm:p-10 text-white shadow-md">
        <div class="max-w-2xl space-y-2">
            <span class="inline-block rounded-md bg-emerald-500/30 px-2.5 py-0.5 text-[10px] font-black uppercase text-emerald-200">
                Fresh Category Collection
            </span>
            <h1 class="text-2xl sm:text-4xl font-black text-white">
                {{ $collection->translateAttribute('name') }}
            </h1>
            <p class="text-xs sm:text-sm text-emerald-100 leading-relaxed">
                {{ $collection->translateAttribute('description') }}
            </p>
        </div>
    </div>

    <!-- Category Filters & Products -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar -->
        <aside class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    All Categories
                </h3>
                <ul class="space-y-2 text-xs font-semibold">
                    @foreach ($collections as $col)
                        @php $colSlug = $col->urls->first()?->slug; @endphp
                        <li>
                            <a href="{{ route('products.category', $colSlug) }}" class="block rounded-lg px-2.5 py-1.5 transition {{ $collection->id === $col->id ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                {{ $col->translateAttribute('name') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <!-- Products -->
        <div class="lg:col-span-3 space-y-6">
            
            <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="text-xs font-bold text-slate-700">
                    Showing <strong>{{ $products->total() }}</strong> products in this category
                </span>

                <form method="GET" class="flex items-center gap-2">
                    <label for="sort" class="text-xs font-bold text-slate-500">Sort:</label>
                    <select id="sort" name="sort" onchange="this.form.submit()" class="rounded-xl border border-slate-200 py-1.5 px-3 text-xs font-semibold text-slate-800">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest</option>
                        <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            @if ($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                    <p class="text-sm font-bold text-slate-600">No products found in this category.</p>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

