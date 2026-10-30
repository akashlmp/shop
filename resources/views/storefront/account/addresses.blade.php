@extends('layouts.app')

@section('title', 'Saved Delivery Addresses - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Saved Delivery Addresses</h1>
            <p class="text-xs text-slate-500 mt-1">Manage home, office, and secondary delivery destinations</p>
        </div>
        <a href="{{ route('account.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: List of saved addresses -->
        <div class="lg:col-span-7 space-y-4">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-900">Your Addresses ({{ $addresses->count() }})</h2>

            @if ($addresses->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($addresses as $addr)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-slate-900">{{ $addr->first_name }} {{ $addr->last_name }}</span>
                                @if ($addr->shipping_default)
                                    <span class="rounded bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase px-2 py-0.5">
                                        Default
                                    </span>
                                @endif
                            </div>
                            <div class="text-slate-600 space-y-0.5">
                                <div>{{ $addr->line_one }}</div>
                                @if ($addr->line_two) <div>{{ $addr->line_two }}</div> @endif
                                <div>{{ $addr->city }}, {{ $addr->state }} - <strong class="font-mono text-slate-800">{{ $addr->postcode }}</strong></div>
                            </div>
                            @if ($addr->contact_phone)
                                <div class="text-slate-500 text-[11px] pt-1">Phone: {{ $addr->contact_phone }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-xs text-slate-500">
                    No addresses saved yet. Use the form on the right to add your primary delivery address.
                </div>
            @endif
        </div>

        <!-- Right: Add New Address Form -->
        <div class="lg:col-span-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
                Add New Delivery Address
            </h2>

            <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Street Address Line 1 *</label>
                    <input type="text" name="line_one" required placeholder="House number and street" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Line 2 (optional)</label>
                    <input type="text" name="line_two" placeholder="Apartment / Landmark" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">City *</label>
                        <input type="text" name="city" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">State *</label>
                        <input type="text" name="state" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Postcode *</label>
                        <input type="text" name="postcode" required placeholder="400001" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Country *</label>
                    <select name="country_id" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                        @foreach ($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Contact Phone</label>
                    <input type="tel" name="contact_phone" placeholder="+44 or +91..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Delivery Instructions (optional)</label>
                    <textarea name="delivery_instructions" rows="2" class="w-full rounded-xl border border-slate-300 py-1.5 px-3 text-xs text-slate-900 focus:ring-emerald-500"></textarea>
                </div>

                <button type="submit" class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 py-2.5 text-xs font-black text-white shadow transition active:scale-95">
                    Save Address
                </button>
            </form>
        </div>

    </div>

</div>
@endsection

