@extends('layouts.app')

@section('title', 'Contact Support & Delivery Hubs - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <div class="text-center max-w-2xl mx-auto space-y-2">
        <span class="rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-3 py-1">
            24/7 Customer Support
        </span>
        <h1 class="text-3xl font-black text-slate-900">We're Here to Help</h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Have questions regarding your 15-minute quick order, wholesale freight dispatch, or product quality? Reach out to our dedicated support team.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Interactive Contact Form -->
        <div class="lg:col-span-7 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-5">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
                Send Us a Message
            </h2>

            <form action="{{ route('page.contact.submit') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Your Full Name *</label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email Address *</label>
                        <input type="email" name="email" required class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Inquiry Subject *</label>
                    <select name="subject" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:ring-emerald-500">
                        <option value="Quick Delivery Status">15-Min Quick Delivery Status</option>
                        <option value="Wholesale Bulk Inquiries">Wholesale / Bulk Commercial Inquiries</option>
                        <option value="Produce Quality & Freshness">Produce Quality &amp; Freshness</option>
                        <option value="Refund & Returns">Refund &amp; Return Request</option>
                        <option value="General Question">General Question</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Your Message *</label>
                    <textarea name="message" rows="5" required placeholder="Describe your inquiry..." class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs text-slate-900 focus:ring-emerald-500"></textarea>
                </div>

                <button type="submit" class="rounded-xl bg-emerald-600 hover:bg-emerald-700 py-3 px-6 text-xs font-black text-white shadow-md shadow-emerald-600/20 transition active:scale-95">
                    Submit Message &rarr;
                </button>
            </form>
        </div>

        <!-- Right: Support Details & Active Hubs -->
        <div class="lg:col-span-5 space-y-6">
            
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4 text-xs">
                <h3 class="font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
                    Customer Care Coordinates
                </h3>
                <div class="space-y-3 text-slate-600 font-medium">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">📞</span>
                        <div>
                            <div class="font-bold text-slate-900">Phone Support</div>
                            <div>+44 20 7946 0991 / +91 22 2490 8000</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xl">✉️</span>
                        <div>
                            <div class="font-bold text-slate-900">Email Inquiries</div>
                            <div>support@lunarcommerce.local</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🏢</span>
                        <div>
                            <div class="font-bold text-slate-900">Central Micro-Fulfillment Operations</div>
                            <div>Unit 12, Terminal Hub, London &bull; Nariman Point Central, Mumbai</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm space-y-3 text-xs">
                <h3 class="font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-200">
                    Active Delivery Hubs ({{ $deliveryAreas->count() }})
                </h3>
                <div class="space-y-2">
                    @foreach ($deliveryAreas as $area)
                        <div class="flex items-center justify-between font-semibold text-slate-700 bg-white p-2 rounded-xl border border-slate-100">
                            <div>
                                <span class="font-bold text-slate-900">{{ $area->city }}</span> &bull; {{ $area->name }}
                            </div>
                            <span class="rounded bg-emerald-100 text-emerald-800 text-[10px] font-black px-1.5 py-0.5">
                                {{ $area->delivery_time_minutes }}m
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

