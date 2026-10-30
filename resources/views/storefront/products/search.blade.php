@extends('layouts.app')

@section('title', 'Search Results for "' . $search . '" - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <nav class="flex items-center text-xs font-semibold text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-bold">Search Results</span>
    </nav>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-xl sm:text-2xl font-black text-slate-900">
            Search Results for <span class="text-emerald-600">"{{ $search }}"</span>
        </h1>
        <p class="text-xs text-slate-500 mt-1">Found {{ $products->total() }} matching grocery products</p>
    </div>

    @if ($products->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-16 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl">
                🔎
            </div>
            <h3 class="mt-4 text-base font-bold text-slate-900">No items matched "{{ $search }}"</h3>
            <p class="mt-1 text-xs text-slate-500">Check spelling or search for common Indian grocery items like mango, rice, ghee, atta, or spices.</p>
            <a href="{{ route('products.index') }}" class="mt-5 inline-block rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-emerald-700">
                Browse Full Catalog
            </a>
        </div>
    @endif

</div>
@endsection

