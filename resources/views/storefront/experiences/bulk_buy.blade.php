@extends('layouts.app')

@section('title', 'B2B Master Carton Wholesale Portal - A&C Electronics')
@section('meta_description', 'Direct OEM master carton wholesale portal. Tiered pricing on 5+, 20+, and 50+ carton consignments with container freight logistics.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Bulk Wholesale Header -->
    <div class="rounded-3xl border border-slate-800 bg-slate-900 text-white p-8 sm:p-12 text-center max-w-4xl mx-auto space-y-3 shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="relative">
            <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-blue-400">
                Powering Global Trade, Building Trust
            </span>
            <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-white mt-1">
                B2B Master Carton Wholesale Portal
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed font-light mt-2">
                Designed for retail shop networks, corporate distributors, and commercial trade resellers. Automated tiered volume margins apply starting from 5+ units and full 50-unit master cartons.
            </p>
        </div>
    </div>

    <!-- Category Pill Filters -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-semibold no-scrollbar">
        <a href="{{ route('experience.bulk') }}" class="rounded-full px-4 py-2 whitespace-nowrap shrink-0 transition {{ !request('category') ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:border-blue-400' }}">
            All Master Cartons
        </a>
        @foreach ($collections as $col)
            @php $slug = $col->urls->first()?->slug; @endphp
            <a href="{{ route('experience.bulk', ['category' => $slug]) }}" class="rounded-full px-4 py-2 whitespace-nowrap shrink-0 transition {{ request('category') === $slug ? 'bg-blue-600 text-white shadow-xs border border-blue-600' : 'bg-white border border-slate-200 text-slate-700 hover:border-blue-400' }}">
                {{ $col->translateAttribute('name') }}
            </a>
        @endforeach
    </div>

    <!-- Bulk Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($products as $product)
            <x-bulk-product-card :product="$product" />
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-slate-200">
                <p class="text-slate-500 text-sm font-light">No master carton models found in this category.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $products->links() }}
    </div>

</div>
@endsection
