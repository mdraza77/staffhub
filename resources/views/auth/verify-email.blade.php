<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StaffHub') }} - Verify Email</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,450,550,650&display=swap" rel="stylesheet" />

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
<body class="font-sans antialiased h-full text-slate-900 bg-slate-50 dark:bg-zinc-950 flex items-center justify-center p-4 transition-colors duration-200">

    <!-- Primary Centered Card Container (Fully Responsive) -->
    <div class="w-full max-w-md bg-white dark:bg-zinc-900 p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800 space-y-6">
        
        <!-- Branding and Title -->
        <div class="text-center space-y-3">
            <div class="inline-flex p-3 bg-indigo-50 text-indigo-650 rounded-xl">
                <!-- Portal Emblem -->
                <svg class="w-8 h-8" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verify Your Email</h1>
            <p class="text-sm text-slate-500 leading-relaxed">
                Thanks for signing up! Before getting started, verify your email address by clicking on the link we just emailed to you. If you didn't receive it, we will resend another.
            </p>
        </div>

        <!-- Verification success alert -->
        @if (session('status') == 'verification-link-sent')
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex gap-3 leading-relaxed">
                <svg class="w-5 h-5 text-emerald-650 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>A new verification link has been sent to the email address you provided.</span>
            </div>
        @endif

        <div class="space-y-4">
            <!-- Action to Resend Email -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                        class="w-full py-3 px-4 text-sm font-semibold tracking-wide text-white rounded-xl bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                    Resend Verification Email
                </button>
            </form>

            <!-- Action to Log Out -->
            <form method="POST" action="{{ route('logout') }}" class="text-center pt-2">
                @csrf
                <button type="submit" class="text-sm font-semibold text-slate-550 hover:text-slate-800 transition-colors focus:outline-none underline">
                    Log Out
                </button>
            </form>
        </div>
    </div>

</body>
</html>
