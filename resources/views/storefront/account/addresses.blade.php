@extends('layouts.app')

@section('title', 'Saved Delivery Hubs - A&C Electronics')
@section('meta_description', 'Manage saved destination addresses and logistics hubs with A&C Electronics.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-950">Saved Delivery Hubs</h1>
            <p class="text-xs text-slate-500 mt-1">Manage delivery destinations for your retail and wholesale hardware orders</p>
        </div>
        <a href="{{ route('account.index') }}" class="text-xs font-bold text-blue-600 hover:text-slate-900">
            &larr; Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: List of saved addresses -->
        <div class="lg:col-span-7 space-y-4">
            <h2 class="font-display text-lg font-bold text-slate-900">Your Locations ({{ $addresses->count() }})</h2>

            @if ($addresses->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($addresses as $addr)
                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-2xs space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm">{{ $addr->first_name }} {{ $addr->last_name }}</span>
                                @if ($addr->shipping_default)
                                    <span class="rounded-full bg-blue-100 text-blue-900 text-[9px] font-bold uppercase px-2 py-0.5">
                                        Default
                                    </span>
                                @endif
                            </div>
                            <div class="text-slate-600 space-y-0.5">
                                <div>{{ $addr->line_one }}</div>
                                @if ($addr->line_two) <div>{{ $addr->line_two }}</div> @endif
                                <div>{{ $addr->city }}, {{ $addr->state }} - <strong class="text-slate-800">{{ $addr->postcode }}</strong></div>
                            </div>
                            @if ($addr->contact_phone)
                                <div class="text-slate-400 text-[11px] pt-1">Phone: {{ $addr->contact_phone }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-xs text-slate-500">
                    No addresses saved yet. Use the form to add your primary delivery destination.
                </div>
            @endif
        </div>

        <!-- Right: Add New Address Form -->
        <div class="lg:col-span-5 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-2xs space-y-4">
            <h2 class="font-display text-lg font-bold text-slate-900 pb-2 border-b border-slate-100">
                Add New Delivery Destination
            </h2>

            <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" required class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Address Line 1 *</label>
                    <input type="text" name="line_one" required placeholder="Street address or warehouse bay" class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Address Line 2 (optional)</label>
                    <input type="text" name="line_two" placeholder="Suite, floor, department" class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">City *</label>
                        <input type="text" name="city" required class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">State/County *</label>
                        <input type="text" name="state" required class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Postcode *</label>
                        <input type="text" name="postcode" required class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Country *</label>
                    <select name="country_id" required class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                        @foreach ($countries as $c)
                            <option value="{{ $c->id }}" {{ $c->iso2 === 'GB' ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Contact Phone</label>
                    <input type="tel" name="contact_phone" placeholder="+44..." class="w-full rounded-full border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:border-blue-600 focus:outline-none">
                </div>

                <button type="submit" class="w-full rounded-full bg-blue-600 hover:bg-blue-700 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow transition">
                    Save Delivery Hub
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
