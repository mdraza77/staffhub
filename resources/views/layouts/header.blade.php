<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Check localStorage or system settings for theme preferences immediately
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logo.png') }}" rel="apple-touch-icon">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.tailwindcss.min.css">
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}?v={{ time() }}">

    @stack('styles')

    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        ::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        html,
        body,
        #sidebar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
            font-size: 14px;
        }
    </style>
</head>

<body
    class="bg-gray-50 text-gray-800 dark:bg-slate-900 dark:text-slate-100 font-sans antialiased transition-all duration-300">

    <header id="header"
        class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm h-16 flex items-center px-4 md:px-6 transition-all duration-300">

        <div class="flex items-center justify-between w-auto md:w-64 gap-4 md:gap-0">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 text-xl font-bold text-blue-700 decoration-none">
                <img class="max-h-10 w-auto object-contain"
                    src="{{ !empty($globalSetting->logo) ? asset('storage/' . $globalSetting->logo) : asset('img/logo.png') }}"
                    alt="{{ $globalSetting->name ?? 'StaffHub Logo' }}"> {{ $globalSetting->name ?? 'StaffHub' }}
            </a>
            <i class="bi bi-list text-2xl cursor-pointer text-gray-800 hover:text-blue-600 transition-colors toggle-sidebar-btn"
                onclick="toggleSidebar()"></i>
        </div>

        <nav class="ml-auto flex items-center">
            <ul class="flex items-center m-0 p-0 list-none gap-5">

                <li class="pr-3 flex items-center">
                    <button id="theme-toggle" onclick="toggleTheme()"
                        class="text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 focus:outline-none transition-colors duration-200">
                        <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-xl hidden"></i>
                        <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-xl"></i>
                    </button>
                </li>

                <li class="relative group pr-3">
                    <a class="flex items-center gap-2 cursor-pointer py-2" href="#">
                        @if (Auth::user()->profile)
                            <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="Profile"
                                class="w-8 h-8 rounded-full object-cover border border-gray-200">
                        @else
                            <img src="{{ asset('img/profile-img.png') }}" alt="Profile"
                                class="w-8 h-8 rounded-full object-cover border border-gray-200">
                        @endif
                        <span
                            class="hidden md:block text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition-colors">
                            {{ Auth::user()->name }}
                        </span>
                    </a>

                    <ul
                        class="absolute right-0 mt-1 w-48 bg-white border border-gray-100 rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">

                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition-colors"
                                href="{{ route('profile.index') }}">
                                <i class="fa-solid fa-user-gear text-lg"></i>
                                <span>Profile</span>
                            </a>
                        </li>

                        <li>
                            <hr class="border-gray-100 my-1">
                        </li>

                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                href="javascript:void(0);" onclick="confirmLogout()">
                                <i class="fa-solid fa-power-off text-lg"></i>
                                <span>Sign Out</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>

    </header>
    <aside id="sidebar"
        class="fixed top-16 left-0 bottom-0 w-64 bg-white border-r border-gray-200 overflow-y-auto z-40 transition-transform duration-300 transform md:translate-x-0 -translate-x-full">

        <ul class="p-4 space-y-1" id="sidebar-nav">

            {{-- ===== DASHBOARD ===== --}}
            @can('Dashboard')
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endcan

            {{-- ===== GROUP: ORGANIZATION ===== --}}
            @if (auth()->check() &&
                    (auth()->user()->can('Employee-Index') ||
                        auth()->user()->can('Employee-Create') ||
                        auth()->user()->can('Department-Index')))
                <li
                    class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
                    Organization
                </li>
            @endif

            @if (auth()->check() && (auth()->user()->can('Employee-Index') || auth()->user()->can('Employee-Create')))
                @can('Department-Index')
                    <li>
                        <a href="{{ route('employees.index') }}"
                            class="{{ request()->routeIs('employees.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                            <i class="fa-solid fa-id-card text-base w-5 text-center"></i>
                            <span>Employee Mgmt</span>
                        </a>
                    </li>
                @endcan
            @endif

            @can('Department-Index')
                <li>
                    <a href="{{ route('departments.index') }}"
                        class="{{ request()->routeIs('departments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-sitemap text-base w-5 text-center"></i>
                        <span>Departments</span>
                    </a>
                </li>
            @endcan

            {{-- ===== GROUP: TIME & OPERATIONS ===== --}}
            @if (auth()->check() &&
                    (auth()->user()->can('Attendance-Index') ||
                        auth()->user()->can('LeaveType-Index') ||
                        auth()->user()->can('Leave-Index') ||
                        auth()->user()->can('Holiday-Index')))
                <li
                    class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
                    Time & Operations
                </li>
            @endif

            @can('Attendance-Index')
                <li>
                    <a href="{{ route('attendance.index') }}"
                        class="{{ request()->routeIs('attendance.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-clipboard-user text-base w-5 text-center"></i>
                        <span>Attendance</span>
                    </a>
                </li>
            @endcan

            @can('LeaveType-Index')
                <li>
                    <a href="{{ route('leave-types.index') }}"
                        class="{{ request()->routeIs('leave-types.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-sliders text-base w-5 text-center"></i>
                        <span>Leave Types</span>
                    </a>
                </li>
            @endcan

            @can('Leave-Index')
                <li>
                    <a href="{{ route('leaves.index') }}"
                        class="{{ request()->routeIs('leaves.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-umbrella-beach text-base w-5 text-center"></i>
                        <span>Leaves</span>
                    </a>
                </li>
            @endcan

            @can('Holiday-Index')
                <li>
                    <a href="{{ route('holidays.index') }}"
                        class="{{ request()->routeIs('holidays.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-calendar-day text-base w-5 text-center"></i>
                        <span>Holidays</span>
                    </a>
                </li>
            @endcan

            {{-- ===== GROUP: WORK ===== --}}
            @if (auth()->check() &&
                    (auth()->user()->can('Task-Index') ||
                        auth()->user()->can('Defect-Index') ||
                        auth()->user()->can('Break-Room-Access') ||
                        auth()->user()->can('BreakType-Manage') ||
                        auth()->user()->can('Break-History-View')))
                <li
                    class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
                    Work & Breaks
                </li>
            @endif

            @can('Task-Index')
                <li>
                    <a href="{{ route('tasks.index') }}"
                        class="{{ request()->routeIs('tasks.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-list-check text-base w-5 text-center"></i>
                        <span>Tasks</span>
                    </a>
                </li>
            @endcan

            @can('Defect-Index')
                <li>
                    <a href="{{ route('defects.index') }}"
                        class="{{ request()->routeIs('defects.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-bug text-base w-5 text-center"></i>
                        <span>Defects</span>
                    </a>
                </li>
            @endcan

            @php
                $breaksActive =
                    request()->routeIs('break-room.*') ||
                    request()->routeIs('break-types.*') ||
                    request()->routeIs('breaks.history');
            @endphp
            @if (auth()->check() &&
                    (auth()->user()->can('Break-Room-Access') ||
                        auth()->user()->can('BreakType-Manage') ||
                        auth()->user()->can('Break-History-View')))
                <li>
                    <details class="group" {{ $breaksActive ? 'open' : '' }}>
                        <summary
                            class="{{ $breaksActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-mug-hot text-base w-5 text-center"></i>
                                <span>Break Room</span>
                            </div>
                            <i
                                class="bi bi-chevron-down transition-transform duration-300 {{ $breaksActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
                        </summary>
                        <ul class="pl-9 pr-2 py-2 space-y-1">
                            @can('Break-Room-Access')
                                <li>
                                    <a href="{{ route('break-room.index') }}"
                                        class="{{ request()->routeIs('break-room.index') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="fa-solid fa-circle text-[6px] w-5 text-center"></i>
                                        <span>Enter Break Room</span>
                                    </a>
                                </li>
                            @endcan
                            @can('Break-History-View')
                                <li>
                                    <a href="{{ route('breaks.history') }}"
                                        class="{{ request()->routeIs('breaks.history') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="fa-solid fa-circle text-[6px] w-5 text-center"></i>
                                        <span>Break History</span>
                                    </a>
                                </li>
                            @endcan
                            @can('BreakType-Manage')
                                <li>
                                    <a href="{{ route('break-types.index') }}"
                                        class="{{ request()->routeIs('break-types.*') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="fa-solid fa-circle text-[6px] w-5 text-center"></i>
                                        <span>Break Types</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif

            {{-- ===== GROUP: MANAGEMENT ===== --}}
            @if (auth()->check() &&
                    (auth()->user()->can('Announcement-Index') ||
                        auth()->user()->can('Salary-View') ||
                        auth()->user()->can('Payslip-Index') ||
                        auth()->user()->can('AccessManagement-Index')))
                <li
                    class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
                    Management
                </li>
            @endif

            @can('Announcement-Index')
                <li>
                    <a href="{{ route('announcements.index') }}"
                        class="{{ request()->routeIs('announcements.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-bullhorn text-base w-5 text-center"></i>
                        <span>Announcements</span>
                    </a>
                </li>
            @endcan

            @php
                $payrollActive = request()->routeIs('payroll.*');
            @endphp
            @if (auth()->check() && (auth()->user()->can('Salary-View') || auth()->user()->can('Payslip-Index')))
                <li title="Payroll Management">
                    <details class="group" {{ $payrollActive ? 'open' : '' }}>
                        <summary
                            class="{{ $payrollActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-file-invoice-dollar text-base w-5 text-center"></i>
                                <span>Payroll Mgmt</span>
                            </div>
                            <i
                                class="bi bi-chevron-down transition-transform duration-300 {{ $payrollActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
                        </summary>
                        <ul class="pl-9 pr-2 py-2 space-y-1">
                            @can('Salary-View')
                                <li>
                                    <a href="{{ route('payroll.salaries.index') }}"
                                        class="{{ request()->routeIs('payroll.salaries.*') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="fa-solid fa-circle text-[6px] w-5 text-center"></i>
                                        <span>Salary Structures</span>
                                    </a>
                                </li>
                            @endcan
                            @can('Payslip-Index')
                                <li>
                                    <a href="{{ route('payroll.payslips.index') }}"
                                        class="{{ request()->routeIs('payroll.payslips.*') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="fa-solid fa-circle text-[6px] w-5 text-center"></i>
                                        <span>Monthly Payslips</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif

            @can('AccessManagement-Index')
                <li>
                    <a href="{{ route('roles.index') }}"
                        class="{{ request()->routeIs('roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-shield-halved text-base w-5 text-center"></i>
                        <span>Roles & Permissions</span>
                    </a>
                </li>
            @endcan

            {{-- ===== GROUP: SYSTEM ===== --}}
            @if (auth()->check() &&
                    (auth()->user()->can('Employee-Index') ||
                        auth()->user()->can('Company-Index') ||
                        auth()->user()->can('Settings-Index')))
                <li
                    class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
                    System
                </li>
            @endif

            @if (auth()->check() && (auth()->user()->can('Employee-Index') || auth()->user()->can('Employee-Create')))
                @php
                    $reportsActive =
                        request()->routeIs('reports.employees') ||
                        request()->routeIs('reports.attendance') ||
                        request()->routeIs('reports.leaves') ||
                        request()->routeIs('reports.departments') ||
                        request()->routeIs('reports.leave-types');
                @endphp
                <li>
                    <details class="group" {{ $reportsActive ? 'open' : '' }}>
                        <summary
                            class="{{ $reportsActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-chart-simple text-base w-5 text-center"></i>
                                <span>Reports</span>
                            </div>
                            <i
                                class="bi bi-chevron-down transition-transform duration-300 {{ $reportsActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
                        </summary>
                        <ul class="pl-9 pr-2 py-2 space-y-1">
                            @can('Employee-Index')
                                <li>
                                    <a href="{{ route('reports.employees') }}"
                                        class="{{ request()->routeIs('reports.employees') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="fa-solid fa-circle text-[6px] w-5 text-center"></i>
                                        <span>Employees Report</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif

            @php
                $settingsActive = request()->routeIs('settings.*') || request()->routeIs('company.*');
            @endphp
            @if (auth()->check() && (auth()->user()->can('Company-Index') || auth()->user()->can('Settings-Index')))
                <li>
                    <details class="group" {{ $settingsActive ? 'open' : '' }}>
                        <summary
                            class="{{ $settingsActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-gear text-base w-5 text-center"></i>
                                <span>Settings</span>
                            </div>
                            <i
                                class="bi bi-chevron-down transition-transform duration-300 {{ $settingsActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
                        </summary>
                        <ul class="pl-9 pr-2 py-2 space-y-1">
                            @can('Company-Index')
                                <li>
                                    <a href="{{ route('company') }}"
                                        class="{{ request()->routeIs('company') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="fa-solid fa-circle text-[6px] w-5 text-center"></i>
                                        <span>Company Setting</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif

        </ul>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Sweetalert logout confirmation --}}
    <script>
        function confirmLogout() {
            Swal.fire({
                title: "Are you sure?",
                text: "You will be signed out of your account.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, sign out"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Signed out",
                        text: "You have been logged out successfully.",
                        icon: "success",
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        document.getElementById('logout-form').submit();
                    });
                }
            });
        }

        // Vanilla JS for Sidebar Toggle Mobile/Desktop (Replaces Bootstrap's toggle functionality)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');

            if (window.innerWidth >= 768) {
                // Desktop toggle logic
                if (sidebar.classList.contains('md:translate-x-0')) {
                    // Currently open, let's close it
                    sidebar.classList.remove('md:translate-x-0');
                    sidebar.classList.add('-translate-x-full');

                    if (main) {
                        main.classList.remove('md:ml-64');
                        main.classList.add('ml-0');
                    }
                } else {
                    // Currently closed, let's open it
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('md:translate-x-0');

                    if (main) {
                        main.classList.remove('ml-0');
                        main.classList.add('md:ml-64');
                    }
                }
            } else {
                // Mobile toggle logic
                sidebar.classList.toggle('-translate-x-full');
            }
        }

        // Close sidebar when clicking outside of it on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.toggle-sidebar-btn');

            // Only trigger on mobile/tablet viewports (< 768px)
            if (window.innerWidth < 768) {
                // If sidebar is currently open (doesn't contain -translate-x-full)
                if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    // Check if the click was outside the sidebar and not on the toggle button
                    if (!sidebar.contains(event.target) && (!toggleBtn || !toggleBtn.contains(event.target))) {
                        sidebar.classList.add('-translate-x-full');
                    }
                }
            }
        });

        // Toggle light/dark theme
        function toggleTheme() {
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            }
        }

        // Initialize icons based on theme
        function initThemeIcon() {
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');

            if (lightIcon && darkIcon) {
                if (document.documentElement.classList.contains('dark')) {
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                } else {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', initThemeIcon);
    </script>
