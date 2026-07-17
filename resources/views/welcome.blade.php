<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StaffHub Portal | Professional Workforce Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon">

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
    class="font-['Inter'] antialiased bg-gray-50 text-gray-800 dark:bg-zinc-950 dark:text-zinc-100 selection:bg-blue-600 selection:text-white transition-colors duration-200">

    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 dark:bg-zinc-950 dark:border-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <!-- App Logo (Placeholder) -->
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-gray-900 dark:text-zinc-50">StaffHub</span>
                    </div>

                    @if (Route::has('login'))
                        <div class="flex space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400 transition px-3 py-2">Log
                                    in</a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center relative overflow-hidden">
            <!-- Background Decorative Blobs -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none opacity-40 z-0">
                <div
                    class="absolute top-10 left-10 w-72 h-72 bg-blue-300 dark:bg-blue-900/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
                </div>
                <div
                    class="absolute top-10 right-10 w-72 h-72 bg-teal-300 dark:bg-teal-900/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
                </div>
                <div
                    class="absolute -bottom-8 left-1/2 w-72 h-72 bg-indigo-300 dark:bg-indigo-900/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000">
                </div>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 py-20">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-zinc-50 tracking-tight mb-6">
                    Streamline Your <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-500">Workforce</span>
                    Management
                </h1>
                <p class="mt-4 text-lg md:text-xl text-gray-600 dark:text-zinc-400 mb-10 max-w-2xl mx-auto">
                    A centralized portal for managing employees, tracking attendance, handling leaves, and generating
                    insightful reports with ease.
                </p>

                @if (Route::has('login'))
                    <div class="flex justify-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-8 py-3 text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-md transition duration-150 ease-in-out">
                                Access Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-8 py-3 text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-md transition duration-150 ease-in-out">
                                Log in to Portal
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 dark:bg-zinc-950 dark:border-zinc-800 py-6">
            <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500 dark:text-zinc-500">
                &copy; {{ date('Y') }} StaffHub Portal. All rights reserved.
            </div>
        </footer>
    </div>

</body>

</html>