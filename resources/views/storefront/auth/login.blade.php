@extends('layouts.app')

@section('title', 'Sign In - A&C Electronics')
@section('meta_description', 'Sign in to your A&C Electronics trade or retail account.')

@section('content')
<div class="mx-auto max-w-md px-4 sm:px-6 py-14 space-y-6">

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-2xs space-y-6">
        
        <div class="text-center space-y-2">
            <div class="flex items-center justify-center gap-2">
                <span class="inline-flex h-7 items-center justify-center rounded-lg bg-blue-600 px-2.5 text-xs font-black tracking-wider text-white shadow-xs">
                    A&amp;C
                </span>
                <span class="font-display text-xl font-bold tracking-tight text-slate-950">A&amp;C ELECTRONICS</span>
            </div>
            <h1 class="font-display text-2xl font-bold text-slate-900 pt-1">Welcome Back</h1>
            <p class="text-xs text-slate-500 font-light">Sign in to track hardware consignments, access B2B wholesale pricing, and manage delivery hubs</p>
        </div>

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Corporate or Personal Email</label>
                <input type="email" name="email" required value="{{ old('email', 'customer@example.com') }}" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                @error('email') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required value="password" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                @error('password') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-blue-600 focus:ring-blue-600">
                    <span>Remember this device</span>
                </label>
                <span class="text-slate-400 text-[11px]">Demo: password</span>
            </div>

            <button type="submit" class="w-full rounded-full bg-blue-600 hover:bg-blue-700 py-3 text-xs font-bold uppercase tracking-wider text-white shadow transition active:scale-95">
                Sign In to Account
            </button>
        </form>

        <div class="border-t border-slate-100 pt-4 text-center text-xs text-slate-500">
            Need a trade account?
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">
                Register here
            </a>
        </div>

    </div>

</div>
@endsection
