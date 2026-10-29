@extends('layouts.app')

@section('title', 'About Our Mandi & E-Commerce Platform - Lunar Store')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-10">

    <div class="text-center space-y-3">
        <span class="rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-3 py-1">
            Our Story &amp; Mission
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
            Bridging Authentic Agriculture with Ultra-Fast Commerce
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-2xl mx-auto leading-relaxed">
            From the fertile orchards of Ratnagiri and the aromatic Tarai basmati belt to your doorstep in 15 minutes.
        </p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-sm space-y-8 text-xs sm:text-sm text-slate-600 leading-relaxed">
        
        <div class="space-y-3">
            <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">1. Authentic Direct Sourcing</h2>
            <p>
                Lunar Commerce was built on the premise that genuine regional staples shouldn't lose their character or nutrition in long, murky supply chains. We partner directly with certified farmer collectives: Alphonso mango growers in Devgad, unpolished dal farmers in Latur, traditional stone-ground chakki millers in Madhya Pradesh, and dairy cooperatives in Gujarat.
            </p>
        </div>

        <div class="space-y-3">
            <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">2. Three Unified Shopping Modes</h2>
            <p>
                We believe customers need different shopping rhythms for different occasions:
            </p>
            <ul class="list-disc list-inside space-y-1 pl-2">
                <li><strong>🛒 Retail Store:</strong> Daily fresh vegetables, milk, bakery loaves, and cooking essentials in standard single-pack quantities.</li>
                <li><strong>📦 Wholesale Bulk Buy:</strong> Heavy 5kg/10kg sacks, master cartons, and wholesale boxes with volume savings for families, caterers, and small businesses.</li>
                <li><strong>⚡ Quick Shop (15-Min Delivery):</strong> Hyperlocal micro-fulfillment centers holding cold-chain items ready for instant dispatch to your home or office.</li>
            </ul>
        </div>

        <div class="space-y-3">
            <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">3. Powered by Lunar PHP &amp; Laravel 11</h2>
            <p>
                Underneath our storefront is a modern, headless e-commerce engine powered by Lunar PHP. We utilize robust database transactions, server-side HMAC cryptographic signature verification for online payments, strict inventory reservation, and reliable tax and shipping pipelines.
            </p>
        </div>

        <div class="rounded-2xl bg-emerald-50 p-6 border border-emerald-200 text-xs font-semibold text-emerald-900 space-y-2">
            <div class="font-extrabold text-sm text-emerald-950">Our Quality Promise</div>
            <p>
                If any fresh produce or grocery item arrives bruised, spoiled, or unsatisfactory, our doorstep return policy ensures instant credits or replacement without complicated questionnaires.
            </p>
        </div>

    </div>

</div>
@endsection
