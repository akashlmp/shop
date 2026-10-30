@extends('layouts.app')

@section('title', 'Terms of Service & Trade - Nexora Global')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Terms of Service &amp; Trade</h1>
        <p class="text-xs text-slate-500 mt-1">Last updated: {{ date('F Y') }}. Legal and operational guidelines governing global purchases.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-sm space-y-6 text-xs sm:text-sm text-slate-600 leading-relaxed">
        
        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">1. Acceptance of Terms</h2>
            <p>
                By accessing this platform, ordering hardware units, or engaging in B2B master carton procurement with Nexora Global, you agree to comply with and be bound by these Terms of Trade &amp; Service.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">2. Pricing, Multi-Currency &amp; Server-Side Integrity</h2>
            <p>
                All prices are represented in minor monetary units and displayed in EUR (€) by default. Browser-submitted pricing is strictly untrusted; unit prices, master carton tier breaks, applicable taxes, and logistics charges are calculated and audited exclusively on the server.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">3. B2B Wholesale &amp; Master Carton Contracts</h2>
            <p>
                Commercial wholesale orders require minimum order quantities (MOQs). Tiered pricing breaks (e.g. 5+, 20+, 50+ pcs) automatically apply when verified cart lines meet the quantity thresholds. Master cartons ship in reinforced export-grade packaging with manufacturer serial numbers.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">4. Freight, Customs &amp; International Delivery</h2>
            <p>
                Standard tracked parcel shipping and palletized commercial air cargo dispatch are offered. Consignments over €50.00 qualify for free tracked standard courier; bulk orders over €250.00 qualify for free commercial pallet freight.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">5. Warranty &amp; Compliance</h2>
            <p>
                All Lenovo and AOC electronic hardware units distributed by Nexora Global carry manufacturer warranties and adhere to international CE, FCC, and RoHS standards. Defective units reported within 14 days of receipt are replaced or credited via the original payment route.
            </p>
        </section>

    </div>

</div>
@endsection
