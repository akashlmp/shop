@extends('layouts.app')

@section('title', 'Search Results for "' . $search . '" - A&C Electronics')
@section('meta_description', 'Search results for ' . $search . ' at A&C Electronics. Certified Lenovo, AOC audio hardware, and smart electronics.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs text-slate-400 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Search Hardware</span>
    </nav>

    <!-- Header -->
    <div class="rounded-3xl bg-slate-900 text-white border border-slate-800 p-6 sm:p-8 shadow-sm">
        <h1 class="font-display text-xl sm:text-3xl font-bold text-white">
            Search Results for <span class="text-blue-400">"{{ $search }}"</span>
        </h1>
        <p class="text-xs text-slate-400 mt-1">Found {{ $products->total() }} matching electronic hardware models</p>
    </div>

    @if ($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-16 text-center max-w-xl mx-auto space-y-4">
            <span class="text-3xl block">🔍</span>
            <h3 class="font-display text-lg font-bold text-slate-900">No hardware models matched "{{ $search }}"</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Try searching for "Lenovo", "AOC", "ANC", "Earbuds", "Smartwatch", or "Wireless Mouse".
            </p>
            <a href="{{ route('products.index') }}" class="inline-block rounded-full bg-blue-600 px-6 py-2.5 text-xs font-bold text-white hover:bg-blue-700 transition">
                Browse Full Catalog &rarr;
            </a>
        </div>
    @endif

</div>
@endsection
