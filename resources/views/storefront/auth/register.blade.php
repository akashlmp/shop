@extends('layouts.app')

@section('title', 'Register Trade Account - A&C Electronics')
@section('meta_description', 'Register an account with A&C Electronics for wholesale access and fast order tracking.')

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
            <h1 class="font-display text-2xl font-bold text-slate-900 pt-1">Create an Account</h1>
            <p class="text-xs text-slate-500 font-light">Join A&amp;C Electronics for tracked consignments, wholesale master carton access, and priority trade desk support</p>
        </div>

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name / Trade Representative</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Marcus Sterling" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                @error('name') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="name@company.com" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                @error('email') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Password (min 8 characters)</label>
                <input type="password" name="password" required class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                @error('password') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
            </div>

            <button type="submit" class="w-full rounded-full bg-blue-600 hover:bg-blue-700 py-3 text-xs font-bold uppercase tracking-wider text-white shadow transition active:scale-95">
                Create Account
            </button>
        </form>

        <div class="border-t border-slate-100 pt-4 text-center text-xs text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">
                Sign In
            </a>
        </div>

    </div>

</div>
@endsection
