<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StaffHub') }} - Reset Password</title>

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
            <div class="inline-flex p-3 bg-indigo-50 text-indigo-650 rounded-xl">
                <!-- Portal Emblem -->
                <svg class="w-8 h-8" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Reset Password</h1>
            <p class="text-sm text-slate-500">Enter your email and define your new password below.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                        autofocus placeholder="name@company.com"
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

            <!-- New Password Form Field with Show/Hide toggle -->
            <div class="space-y-2" x-data="{ show: false }">
                <label for="password" class="text-sm font-semibold tracking-wide text-slate-700">
                    New Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required
                        placeholder="••••••••"
                        class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-505 transition-all duration-150" />

                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-650 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" x-show="!show" stroke="currentColor" fill="none" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="w-5 h-5" x-show="show" x-cloak stroke="currentColor" fill="none" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                @if ($errors->has('password'))
                    <div class="flex items-center gap-1.5 mt-1.5 text-xs text-rose-600 font-medium">
                        <svg class="w-4 h-4 shrink-0" stroke="currentColor" fill="none" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ $errors->first('password') }}</span>
                    </div>
                @endif
            </div>

            <!-- Confirm Password Form Field with Show/Hide toggle -->
            <div class="space-y-2" x-data="{ showConfirm: false }">
                <label for="password_confirmation" class="text-sm font-semibold tracking-wide text-slate-700">
                    Confirm Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                        name="password_confirmation" required placeholder="••••••••"
                        class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-505 transition-all duration-150" />

                    <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-655 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" x-show="!showConfirm" stroke="currentColor" fill="none" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="w-5 h-5" x-show="showConfirm" x-cloak stroke="currentColor" fill="none"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                @if ($errors->has('password_confirmation'))
                    <div class="flex items-center gap-1.5 mt-1.5 text-xs text-rose-600 font-medium">
                        <svg class="w-4 h-4 shrink-0" stroke="currentColor" fill="none" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ $errors->first('password_confirmation') }}</span>
                    </div>
                @endif
            </div>

            <!-- Submit action button -->
            <button type="submit"
                class="w-full py-3 px-4 mt-6 text-sm font-semibold tracking-wide text-white rounded-xl bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                Reset Password
            </button>
        </form>
    </div>

</body>

</html>