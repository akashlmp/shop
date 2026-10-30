@extends('layouts.app')

@section('title', 'Trade Desk & Support - Nexora Global')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <div class="text-center max-w-2xl mx-auto space-y-2">
        <span class="rounded-full bg-indigo-100 text-indigo-800 text-[10px] font-black uppercase px-3 py-1">
            Global Trade &amp; Client Support
        </span>
        <h1 class="text-3xl font-black text-slate-900">Contact Nexora Global</h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Have questions regarding master carton orders, air freight dispatch, container allocations, or product specifications? Contact our trade desk.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Contact Form -->
        <div class="lg:col-span-7 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-5">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
                Send an Inquiry
            </h2>

            <form action="{{ route('page.contact.submit') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Your Full Name *</label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Corporate / Personal Email *</label>
                        <input type="email" name="email" required class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Inquiry Subject *</label>
                    <select name="subject" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="B2B Master Carton Order">B2B Wholesale / Master Carton Order</option>
                        <option value="Container Export Sourcing">Full Container Load (FCL/LCL) Sourcing</option>
                        <option value="Consignment Tracking">Consignment Tracking &amp; Air Freight</option>
                        <option value="Technical Specs & Compliance">Technical Specs &amp; CE/RoHS Inquiries</option>
                        <option value="General Question">General Inquiry</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Your Message *</label>
                    <textarea name="message" rows="5" required placeholder="Describe your required quantities, models, destination country..." class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 py-3 px-6 text-xs font-black text-white shadow-md shadow-indigo-600/30 transition active:scale-95">
                    Submit Trade Inquiry &rarr;
                </button>
            </form>
        </div>

        <!-- Right: Global Offices & Hubs -->
        <div class="lg:col-span-5 space-y-6">
            
            <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
                    Global Distribution Desks
                </h3>

                <div class="space-y-4 text-xs text-slate-600">
                    <div>
                        <div class="font-bold text-slate-900">United Kingdom &amp; European Logistics</div>
                        <div class="text-[11px] text-slate-500">25 Bank Street, Canary Wharf, London E14 5JP</div>
                        <div class="font-semibold text-indigo-600 mt-0.5">Phone: +44 20 7946 0991</div>
                    </div>

                    <div class="border-t border-slate-100 pt-3">
                        <div class="font-bold text-slate-900">Asia-Pacific Distribution Center</div>
                        <div class="text-[11px] text-slate-500">Bandra Kurla Complex, Mumbai, Maharashtra 400051</div>
                        <div class="font-semibold text-indigo-600 mt-0.5">Phone: +91 22 6123 4567</div>
                    </div>

                    <div class="border-t border-slate-100 pt-3">
                        <div class="font-bold text-slate-900">Direct Email Support</div>
                        <div class="text-[11px] text-slate-500">trade@nexoraglobal.com &bull; support@nexoraglobal.com</div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-900 text-white p-6 shadow-sm space-y-2">
                <div class="text-cyan-400 font-black text-xs uppercase tracking-wider">Trading Hours</div>
                <div class="text-xs text-slate-300">
                    Our international logistics team monitors freight tracking 24/7. Account managers available Monday - Friday: 08:00 - 20:00 UTC.
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
