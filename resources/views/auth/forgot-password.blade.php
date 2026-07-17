<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StaffHub') }} - Forgot Password</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,450,550,650&display=swap" rel="stylesheet" />

    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">

    <!-- Dark Mode Theme Initializer Script -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="font-sans antialiased h-full text-slate-900 bg-slate-50 dark:bg-zinc-950 flex items-center justify-center p-4 transition-colors duration-200">

    <!-- Primary Centered Card Container (Fully Responsive) -->
    <div
        class="w-full max-w-md bg-white dark:bg-zinc-900 p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800 space-y-6">

        <!-- Branding and Title -->
        <div class="text-center space-y-3">
            <a href="/" class="inline-flex p-3 bg-indigo-50 text-indigo-650 rounded-xl">
                <!-- Portal Emblem -->
                <svg class="w-8 h-8" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
            </a>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Forgot Password?</h1>
            <p class="text-sm text-slate-500 leading-relaxed font-normal">
                No problem. Enter your email address below and we will email you a password reset link.
            </p>
        </div>

        <!-- Session Status (e.g. password resets logs) -->
        @if (session('status'))
            <div
                class="p-4 rounded-xl bg-emerald-50 border border-emerald-250 text-emerald-800 text-sm font-medium flex gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address Form Field -->
            <div class="space-y-2">
                <label for="email" class="text-sm font-semibold tracking-wide text-slate-700">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="name@company.com"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-505 transition-all duration-150" />
                </div>
                <!-- Validation Error display -->
                @if ($errors->has('email'))
                    <div class="flex items-center gap-1.5 mt-1.5 text-xs text-rose-600 font-medium">
                        <svg class="w-4 h-4 shrink-0" stroke="currentColor" fill="none" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif
            </div>

            <!-- Submit action button and navigation back link -->
            <div class="space-y-4">
                <button type="submit"
                    class="w-full py-3 px-4 text-sm font-semibold tracking-wide text-white rounded-xl bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                    Email Password Reset Link
                </button>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold text-indigo-650 hover:text-indigo-800 transition-colors">
                        Back to Log In
                    </a>
                </div>
            </div>
        </form>
    </div>

</body>

</html>