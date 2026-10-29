@extends('layouts.app')

@section('title', 'Retail Grocery Shopping - Fresh Daily Harvest')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-emerald-700 to-green-800 p-6 sm:p-10 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <span class="inline-block rounded-md bg-white/20 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-emerald-100">
                🛒 Retail Shopping Experience
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Daily Fresh Groceries for Your Home</h1>
            <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                Order everyday quantities: 1kg apples, 500g paneer, 1L milk, and kitchen staples with standard or express home delivery. Free delivery on orders €35+.
            </p>
        </div>
        <div class="rounded-2xl bg-white/10 backdrop-blur-md p-4 border border-white/20 text-xs text-white space-y-1 shrink-0">
            <div class="font-bold text-amber-300">💡 Shopping Tip:</div>
            <div>Need wholesale quantities for events or resale?</div>
            <a href="{{ route('experience.bulk') }}" class="font-bold underline text-white hover:text-amber-300 transition">Switch to Bulk Buy &rarr;</a>
        </div>
    </div>

    <!-- Category Pill Filter Tabs -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 text-xs font-bold no-scrollbar">
        <a href="{{ route('experience.retail') }}" class="rounded-xl px-4 py-2 transition {{ !request('category') ? 'bg-emerald-600 text-white shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-emerald-400' }}">
            All Retail
        </a>
        @foreach ($collections as $col)
            @php $slug = $col->urls->first()?->slug; @endphp
            <a href="{{ route('experience.retail', ['category' => $slug]) }}" class="rounded-xl px-4 py-2 transition shrink-0 {{ request('category') === $slug ? 'bg-emerald-600 text-white shadow' : 'bg-white border border-slate-200 text-slate-700 hover:border-emerald-400' }}">
                {{ $col->translateAttribute('name') }}
            </a>
        @endforeach
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach ($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
@endsection
