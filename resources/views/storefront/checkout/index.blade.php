@extends('layouts.app')

@section('title', 'Secure Checkout - Lunar Store')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Checkout</h1>
        <p class="text-xs text-slate-500 mt-1">Please provide your delivery details, select your preferred shipping and payment method</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Form: Contact, Address, Shipping, Payment -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. Customer Contact Details -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white text-xs">1</span>
                        <span>Contact Information</span>
                    </h2>

                    @php
                        $user = Auth::user();
                        $savedCustomer = $user?->latestCustomer();
                        $nameParts = explode(' ', $user?->name ?? '', 2);
                        $defaultFirst = old('first_name', $savedCustomer?->first_name ?? $nameParts[0] ?? '');
                        $defaultLast = old('last_name', $savedCustomer?->last_name ?? $nameParts[1] ?? '');
                        $defaultEmail = old('contact_email', $user?->email ?? '');
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" required value="{{ $defaultFirst }}" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            @error('first_name') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Last Name *</label>
                            <input type="text" name="last_name" required value="{{ $defaultLast }}" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            @error('last_name') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address *</label>
                            <input type="email" name="contact_email" required value="{{ $defaultEmail }}" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            @error('contact_email') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number *</label>
                            <input type="tel" name="contact_phone" required value="{{ old('contact_phone', '+44 7700 900077') }}" placeholder="+44 or +91..." class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            @error('contact_phone') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Delivery Address -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white text-xs">2</span>
                        <span>Delivery Address</span>
                    </h2>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Street Address Line 1 *</label>
                        <input type="text" name="line_one" required value="{{ old('line_one', '10 Downing Street / Flat 4B Orchard Green') }}" placeholder="House number and street name" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                        @error('line_one') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Apartment, suite, unit (optional)</label>
                        <input type="text" name="line_two" value="{{ old('line_two') }}" placeholder="Apartment / Landmark" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">City *</label>
                            <input type="text" name="city" required value="{{ old('city', 'London') }}" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            @error('city') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">State / County *</label>
                            <input type="text" name="state" required value="{{ old('state', 'Greater London') }}" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            @error('state') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Postal Code *</label>
                            <input type="text" name="postcode" required value="{{ old('postcode', session('quick_postcode', 'SW1A 1AA')) }}" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-bold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            @error('postcode') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country *</label>
                        <select name="country_id" required class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ $country->id === $defaultCountry->id ? 'selected' : '' }}>
                                    {{ $country->name }} ({{ $country->iso2 }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Special Delivery Instructions (optional)</label>
                        <textarea name="delivery_instructions" rows="2" placeholder="e.g. Ring the doorbell, leave at front reception, gate code #1234" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs text-slate-900 focus:ring-emerald-500"></textarea>
                    </div>
                </div>

                <!-- 3. Shipping Options -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white text-xs">3</span>
                        <span>Shipping Method</span>
                    </h2>

                    <div class="space-y-3">
                        @foreach ($shippingOptions as $index => $opt)
                            @php
                                $optPriceVal = $opt->price->value;
                                $isFree = $optPriceVal === 0;
                            @endphp
                            <label class="flex items-center justify-between rounded-xl border border-slate-200 p-4 hover:border-emerald-500 cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="shipping_option" value="{{ $opt->getIdentifier() }}" {{ $index === 0 ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">{{ $opt->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $opt->description }}</div>
                                    </div>
                                </div>
                                <div class="text-xs font-black text-slate-900">
                                    {{ $isFree ? 'FREE' : '€' . number_format($optPriceVal / 100, 2) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Payment Method -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white text-xs">4</span>
                        <span>Payment Method</span>
                    </h2>

                    <div class="space-y-3" x-data="{ paymentType: 'cod' }">
                        
                        <!-- COD Option -->
                        <label class="flex items-start justify-between rounded-xl border-2 p-4 cursor-pointer transition" :class="paymentType === 'cod' ? 'border-emerald-600 bg-emerald-50/40' : 'border-slate-200 bg-white'">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentType" class="mt-0.5 h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">💵 Cash on Delivery (COD) / Pay on Delivery</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Pay conveniently with cash or scan UPI QR directly to the delivery rider upon arrival.</div>
                                </div>
                            </div>
                            <span class="rounded bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-2 py-0.5">Zero Fee</span>
                        </label>

                        <!-- Razorpay Online Payment -->
                        <label class="flex items-start justify-between rounded-xl border-2 p-4 cursor-pointer transition" :class="paymentType === 'razorpay' ? 'border-emerald-600 bg-emerald-50/40' : 'border-slate-200 bg-white'">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="razorpay" x-model="paymentType" class="mt-0.5 h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">💳 Online Payment via Razorpay (Cards, UPI, NetBanking)</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Instant secure payment verification. Server-side HMAC SHA256 validated. Sandbox enabled.</div>
                                </div>
                            </div>
                            <span class="rounded bg-indigo-100 text-indigo-800 text-[10px] font-extrabold px-2 py-0.5">Instant Confirm</span>
                        </label>

                    </div>
                </div>

            </div>

            <!-- Right Summary Sticky Column -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
                        Order Items ({{ $summary['total_items'] }})
                    </h3>

                    <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto pr-1">
                        @foreach ($summary['lines'] as $line)
                            <div class="py-2.5 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $line['image_url'] }}" alt="{{ $line['name'] }}" class="h-10 w-10 rounded-lg object-cover bg-slate-50 border border-slate-100">
                                    <div>
                                        <div class="font-bold text-slate-800 line-clamp-1">{{ $line['name'] }}</div>
                                        <div class="text-[11px] text-slate-400">Qty: {{ $line['quantity'] }} &bull; {{ $line['unit_price_formatted'] }}</div>
                                    </div>
                                </div>
                                <div class="font-extrabold text-slate-900 shrink-0">
                                    {{ $line['sub_total_formatted'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-100 pt-3 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-900">{{ $summary['sub_total_formatted'] }}</span>
                        </div>
                        @if ($summary['discount_total'] > 0)
                            <div class="flex justify-between text-emerald-700 font-bold">
                                <span>Discount ({{ $summary['coupon_code'] }})</span>
                                <span>-{{ $summary['discount_total_formatted'] }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping</span>
                            <span>{{ $summary['shipping_total'] > 0 ? $summary['shipping_total_formatted'] : 'FREE' }}</span>
                        </div>
                        <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                            <span class="text-sm font-black text-slate-900">Total Payable</span>
                            <span class="text-2xl font-black text-emerald-700">{{ $summary['total_formatted'] }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 py-3.5 px-4 text-sm font-black text-white shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center justify-center gap-2">
                        <span>Place Order Now</span>
                        <span>&rarr;</span>
                    </button>

                    <p class="text-[11px] text-center text-slate-400">
                        🔒 Safe &amp; Encrypted 256-bit SSL Checkout.
                    </p>
                </div>

            </div>

        </div>

    </form>

</div>
@endsection

