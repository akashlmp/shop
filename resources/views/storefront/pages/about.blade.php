@extends('layouts.app')

@section('title', 'About Us - A&C Electronics')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-10">

    <div class="text-center space-y-3">
        <span class="rounded-full bg-indigo-100 text-indigo-800 text-[10px] font-black uppercase px-3.5 py-1">
            Our Mission &amp; Trade Infrastructure
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
            Connecting Markets, Delivering Excellence
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-2xl mx-auto leading-relaxed">
            Direct factory distribution and international commerce for certified Lenovo acoustic systems, AOC gaming gear, ANC wireless audio, and consumer peripherals.
        </p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-sm space-y-8 text-xs sm:text-sm text-slate-600 leading-relaxed">
        
        <div class="space-y-3">
            <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">1. Direct OEM &amp; Factory Alliances</h2>
            <p>
                A&C Electronics was established to streamline cross-border consumer electronics procurement. By eliminating redundant middlemen and working directly with manufacturer production lines, we provide certified genuine Lenovo audio devices and AOC peripherals with guaranteed CE, FCC, and RoHS certifications.
            </p>
        </div>

        <div class="space-y-3">
            <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">2. Dual Commerce Architecture: Retail &amp; Master Carton Wholesale</h2>
            <p>
                Our platform seamlessly accommodates both single-unit consumers and commercial wholesale buyers:
            </p>
            <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-700 font-medium">
                <li><strong>🛍️ Retail Showcase:</strong> Individual units of flagship TWS earbuds, ANC headsets, smartwatches, and mice with fast tracked delivery.</li>
                <li><strong>📦 B2B Wholesale Portal:</strong> Transparent volume tier breaks starting at 5 units, with deep wholesale carton rates (50–100 pcs) for commercial resellers, regional retail chains, and corporate procurement.</li>
            </ul>
        </div>

        <div class="space-y-3">
            <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">3. Built on Enterprise Laravel 11 &amp; Lunar PHP</h2>
            <p>
                A&C Electronics is engineered with high-performance Laravel 11 and Lunar headless commerce architecture. Our platform guarantees real-time stock reservations, cryptographic HMAC SHA256 payment signature verification, integer-precision multi-currency calculation, and dynamic AJAX cart interactions.
            </p>
        </div>

        <div class="rounded-2xl bg-indigo-50 p-6 border border-indigo-200 text-xs font-semibold text-indigo-950 space-y-2">
            <div class="font-extrabold text-sm text-indigo-900">Our International Trade Guarantee</div>
            <p>
                Every master carton ships with factory anti-tamper security seals, full commercial packing lists, and manufacturer warranties.
            </p>
        </div>

    </div>

</div>
@endsection
