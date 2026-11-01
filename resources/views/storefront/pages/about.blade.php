@extends('layouts.app')

@section('title', 'About Us - A&C Electronics')
@section('meta_description', 'Learn about A&C Electronics: Connecting Global Brands to UK Markets. Powering global trade, building trusted partnerships, and delivering OEM excellence.')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    <!-- Header -->
    <div class="text-center space-y-3">
        <span class="rounded-full bg-blue-100 text-blue-900 text-[10px] font-bold uppercase tracking-wider px-3.5 py-1">
            Global Trade &bull; Trusted Partnerships
        </span>
        <h1 class="font-display text-3xl sm:text-5xl font-black text-slate-950">
            Connecting Global Brands to UK Markets
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto leading-relaxed font-light">
            A&amp;C Electronics is your gateway to global markets. We power international electronics trade, bridging licensed OEM hardware directly to UK retail chains, wholesalers, and tech buyers.
        </p>
    </div>

    <!-- Official Commitments Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-2xs space-y-1.5 tech-card-hover">
            <span class="text-blue-600 font-bold text-xs uppercase tracking-wider">Pillar 1</span>
            <h3 class="font-display font-bold text-base text-slate-900">Powering Global Trade, Building Trust</h3>
            <p class="text-xs text-slate-500">We establish direct OEM manufacturer agreements, eliminating middlemen markups and verifying factory compliance for Lenovo and AOC electronics.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-2xs space-y-1.5 tech-card-hover">
            <span class="text-blue-600 font-bold text-xs uppercase tracking-wider">Pillar 2</span>
            <h3 class="font-display font-bold text-base text-slate-900">Your Gateway to Global Markets</h3>
            <p class="text-xs text-slate-500">From our London logistics centre, we provide end-to-end UK customs clearance, warehousing, and express nationwide pallet distribution.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-2xs space-y-1.5 tech-card-hover">
            <span class="text-blue-600 font-bold text-xs uppercase tracking-wider">Pillar 3</span>
            <h3 class="font-display font-bold text-base text-slate-900">Connecting Markets, Delivering Excellence</h3>
            <p class="text-xs text-slate-500">Each consignment is batch-inspected for CE, RoHS, and UKCA safety marks. Every item carries our 12-month UK replacement guarantee.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-2xs space-y-1.5 tech-card-hover">
            <span class="text-blue-600 font-bold text-xs uppercase tracking-wider">Pillar 4</span>
            <h3 class="font-display font-bold text-base text-slate-900">Global Trade. Trusted Partnerships.</h3>
            <p class="text-xs text-slate-500">Long-term commercial supplier relationships with high street retailers, enterprise clients, and independent electronic stores.</p>
        </div>
    </div>

    <!-- Editorial Story Body -->
    <div class="rounded-3xl border border-slate-200 bg-white p-8 sm:p-12 shadow-2xs space-y-8 text-xs sm:text-sm text-slate-600 leading-relaxed font-light">
        
        <div class="space-y-3">
            <h2 class="font-display text-xl font-bold text-slate-900">1. Certified Hardware &amp; Direct Factory Allotments</h2>
            <p>
                Founded on principles of trade integrity and technological excellence, A&amp;C Electronics works directly with premier electronic factories across Asia and Europe. We represent authorized production runs for Lenovo acoustic hardware, AOC gaming audio, Nordic-chipset smartwatches, and high-precision computer peripherals.
            </p>
        </div>

        <div class="space-y-3">
            <h2 class="font-display text-xl font-bold text-slate-900">2. Master Carton Wholesale &amp; Freight Infrastructure</h2>
            <p>
                Our London logistics hub manages scheduled container sea freight, express air consignments, and bulk master carton warehousing. Commercial clients benefit from automated tiered volume pricing (5+, 20+, 50+ units), VAT invoicing, and bonded logistics services.
            </p>
        </div>

        <div class="space-y-3">
            <h2 class="font-display text-xl font-bold text-slate-900">3. CE, RoHS &amp; UKCA Regulatory Assurance</h2>
            <p>
                Every electronic unit imported and distributed through A&amp;C Electronics conforms strictly with UK and European statutory electrical safety standards, battery transport safety certifications (UN38.3), and environmental directives (WEEE &amp; RoHS).
            </p>
        </div>

        <div class="rounded-2xl bg-slate-50 p-6 border border-slate-200 text-xs text-slate-800 space-y-1.5">
            <h3 class="font-display font-bold text-sm text-slate-900">The A&amp;C Electronics Trade Desk</h3>
            <p class="text-slate-600 font-light">
                Have a wholesale distribution requirement or want to discuss container freight? Connect directly with our trade team at <strong class="text-slate-900">+44 20 7946 0991</strong> or via email at <strong class="text-slate-900">trade@ac-electronics.co.uk</strong>.
            </p>
        </div>

    </div>

</div>
@endsection
