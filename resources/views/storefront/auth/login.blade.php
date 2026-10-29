@extends('layouts.app')

@section('title', 'Customer Sign In - Lunar Store')

@section('content')
<div class="mx-auto max-w-md px-4 sm:px-6 py-12 space-y-6">

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="text-center space-y-2">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 text-xl font-bold">
                🛒
            </div>
            <h1 class="text-2xl font-black text-slate-900">Welcome Back</h1>
            <p class="text-xs text-slate-500">Sign in to track orders, save delivery addresses, and enjoy faster checkout</p>
        </div>

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" required value="{{ old('email', 'customer@example.com') }}" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                @error('email') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required value="password" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                @error('password') <span class="text-[11px] font-bold text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-emerald-600 focus:ring-emerald-500">
                    <span>Remember me</span>
                </label>
                <span class="text-slate-400 text-[11px]">Demo: password</span>
            </div>

            <button type="submit" class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 py-3 text-xs font-black text-white shadow-md shadow-emerald-600/20 transition active:scale-95">
                Sign In to Account
            </button>
        </form>

        <div class="border-t border-slate-100 pt-4 text-center text-xs text-slate-500">
            Don't have an account yet?
            <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:underline">
                Create an account
            </a>
        </div>

    </div>

</div>
@endsection
