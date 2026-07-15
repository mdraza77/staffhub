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
        #sidebar,
        #sidebar-scroll {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
            font-size: 14px;
        }

        #sidebar-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Collapsed Sidebar CSS */
        @media (min-width: 768px) {
            #sidebar.collapsed {
                width: 5rem !important;
                /* Narrow width like Instagram */
            }

            #sidebar.collapsed #sidebar-scroll {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            #sidebar.collapsed #sidebar-nav span {
                display: none !important;
            }

            #sidebar.collapsed .sidebar-group-header {
                display: none !important;
            }

            #sidebar.collapsed #sidebar-nav li a,
            #sidebar.collapsed #sidebar-nav li details summary {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            #sidebar.collapsed #sidebar-nav li a i,
            #sidebar.collapsed #sidebar-nav li details summary i.fa-solid {
                margin-right: 0 !important;
                font-size: 1.25rem !important;
                width: 100% !important;
                text-align: center !important;
            }

            /* Hide details dropdown submenus/chevron arrows in collapsed mode */
            #sidebar.collapsed details summary i.fa-chevron-down {
                display: none !important;
            }

            #sidebar.collapsed details ul {
                display: none !important;
            }

            /* Adjust Main and Header positions */
            #main.collapsed {
                margin-left: 5rem !important;
            }

            #header-logo-container.collapsed {
                width: 5rem !important;
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            #header-logo-container.collapsed .header-logo-text {
                display: none !important;
            }
        }

        /* Prevent double-tap hover bug on touch screens/mobile devices */
        @media (hover: none) {

            #sidebar-nav a:hover,
            #sidebar-nav summary:hover {
                background-color: transparent !important;
                color: inherit !important;
            }

            .dark #sidebar-nav a:hover,
            .dark #sidebar-nav summary:hover {
                background-color: transparent !important;
                color: inherit !important;
            }
        }
    </style>
</head>

<body
    class="bg-gray-50 text-gray-800 dark:bg-slate-900 dark:text-slate-100 font-sans antialiased transition-all duration-300">

    <header id="header"
        class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm h-16 flex items-center px-4 md:px-6 transition-all duration-300">

        <div class="flex items-center justify-between w-auto md:w-64 gap-4 md:gap-0" id="header-logo-container"
            class="transition-all duration-300">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 text-xl font-bold text-blue-700 decoration-none">
                <img class="max-h-10 w-auto object-contain"
                    src="{{ !empty($globalSetting->logo) ? asset('storage/' . $globalSetting->logo) : asset('img/logo.png') }}"
                    alt="{{ $globalSetting->name ?? 'StaffHub Logo' }}">
                <span
                    class="header-logo-text text-gray-800 dark:text-zinc-200 font-bold transition-all duration-300">{{ $globalSetting->name ?? 'StaffHub' }}</span>
            </a>
            <i class="fa-solid fa-bars text-2xl cursor-pointer text-gray-800 hover:text-blue-600 transition-colors toggle-sidebar-btn md:hidden"
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

                <li class="relative pr-3">
                    <a class="flex items-center gap-2 cursor-pointer py-2" id="profile-dropdown-trigger"
                        href="javascript:void(0);">
                        @if (Auth::user()->profile)
                            <img src="{{ auth()->user()->profile }}" alt="Profile"
                                class="w-8 h-8 rounded-full object-cover border border-gray-200">
                        @else
                            <img src="{{ asset('img/profile-img.png') }}" alt="Profile"
                                class="w-8 h-8 rounded-full object-cover border border-gray-200">
                        @endif
                        <span
                            class="hidden md:block text-sm font-semibold text-gray-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400 transition-colors">
                            {{ explode(' ', Auth::user()->name)[0] }}
                        </span>
                    </a>

                    <ul id="profile-dropdown-menu"
                        class="absolute right-0 mt-1 w-48 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-lg shadow-lg py-2 opacity-0 invisible transition-all duration-200 z-50">

                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                href="{{ route('profile.index') }}">
                                <i class="fa-solid fa-user-gear text-lg"></i>
                                <span>My Profile</span>
                            </a>
                        </li>

                        <li>
                            <hr class="border-gray-100 dark:border-zinc-800 my-1">
                        </li>

                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors"
                                href="javascript:void(0);" onclick="confirmLogout()">
                                {{-- <i class="fa-solid fa-power-off text-lg"></i> --}}
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
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
        class="fixed top-16 left-0 bottom-0 w-64 bg-white dark:bg-zinc-950 border-r border-gray-200 dark:border-zinc-800 z-40 transition-all duration-300 transform md:translate-x-0 -translate-x-full overflow-visible">

        <!-- Desktop Floating Toggle Button -->
        <button id="sidebar-toggle-btn" onclick="toggleSidebar()"
            class="hidden md:flex absolute top-4 -right-5 w-10 h-10 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-850 rounded-full items-center justify-center cursor-pointer shadow-md hover:bg-gray-50 dark:hover:bg-zinc-800 transition-all duration-200 focus:outline-none z-50">
            <i id="sidebar-toggle-icon" class="fa-solid fa-chevron-left text-base text-gray-600 dark:text-zinc-400"></i>
        </button>

        <div class="h-full overflow-y-auto py-4 px-3" id="sidebar-scroll">
            <ul class="space-y-1" id="sidebar-nav">

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
                @if (
                        auth()->check() &&
                        (auth()->user()->can('Employee-Index') ||
                            auth()->user()->can('Employee-Create') ||
                            auth()->user()->can('Department-Index'))
                    )
                    <li
                        class="sidebar-group-header text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
                        Organization
                    </li>
                @endif

                @if (auth()->check() && (auth()->user()->can('Employee-Index') || auth()->user()->can('Employee-Create')))
                    @can('Employee-Index')
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
                @if (
                        auth()->check() &&
                        (auth()->user()->can('Attendance-Index') ||
                            auth()->user()->can('LeaveType-Index') ||
                            auth()->user()->can('Leave-Index') ||
                            auth()->user()->can('Holiday-Index'))
                    )
                    <li
                        class="sidebar-group-header text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
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
                @if (
                        auth()->check() &&
                        (auth()->user()->can('Task-Index') ||
                            auth()->user()->can('Defect-Index') ||
                            auth()->user()->can('Break-Room-Access') ||
                            auth()->user()->can('BreakType-Manage') ||
                            auth()->user()->can('Break-History-View'))
                    )
                    <li
                        class="sidebar-group-header text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
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
                @if (
                        auth()->check() &&
                        (auth()->user()->can('Break-Room-Access') ||
                            auth()->user()->can('BreakType-Manage') ||
                            auth()->user()->can('Break-History-View'))
                    )
                    <li>
                        <details class="group" {{ $breaksActive ? 'open' : '' }}>
                            <summary
                                class="{{ $breaksActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-mug-hot text-base w-5 text-center"></i>
                                    <span>Break Room</span>
                                </div>
                                <i
                                    class="fa-solid fa-chevron-down transition-transform duration-300 {{ $breaksActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
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
                @if (
                        auth()->check() &&
                        (auth()->user()->can('Announcement-Index') ||
                            auth()->user()->can('Salary-View') ||
                            auth()->user()->can('Payslip-Index') ||
                            auth()->user()->can('AccessManagement-Index'))
                    )
                    <li
                        class="sidebar-group-header text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
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
                                    class="fa-solid fa-chevron-down transition-transform duration-300 {{ $payrollActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
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
                @if (
                        auth()->check() &&
                        (auth()->user()->can('Employee-Index') ||
                            auth()->user()->can('Employee-Create') ||
                            auth()->user()->can('Company-Index') ||
                            auth()->user()->can('Settings-Index'))
                    )
                    <li
                        class="sidebar-group-header text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest px-3 pt-5 pb-1.5 block select-none">
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
                                    class="fa-solid fa-chevron-down transition-transform duration-300 {{ $reportsActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
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
                                    class="fa-solid fa-chevron-down transition-transform duration-300 {{ $settingsActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
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
        </div>
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
            const logoContainer = document.getElementById('header-logo-container');
            const toggleIcon = document.getElementById('sidebar-toggle-icon');

            if (window.innerWidth >= 768) {
                // Desktop toggle logic: toggle 'collapsed' state
                sidebar.classList.toggle('collapsed');
                if (main) main.classList.toggle('collapsed');
                if (logoContainer) logoContainer.classList.toggle('collapsed');

                // Toggle icon
                if (toggleIcon) {
                    if (sidebar.classList.contains('collapsed')) {
                        toggleIcon.classList.remove('fa-chevron-left');
                        toggleIcon.classList.add('fa-bars');
                        localStorage.setItem('sidebar-collapsed', 'true');
                    } else {
                        toggleIcon.classList.remove('fa-bars');
                        toggleIcon.classList.add('fa-chevron-left');
                        localStorage.setItem('sidebar-collapsed', 'false');
                    }
                }
            } else {
                // Mobile toggle logic: slide in / out
                sidebar.classList.toggle('-translate-x-full');
            }
        }

        // Restore sidebar collapsed state on desktop
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const logoContainer = document.getElementById('header-logo-container');
            const toggleIcon = document.getElementById('sidebar-toggle-icon');

            if (window.innerWidth >= 768) {
                if (localStorage.getItem('sidebar-collapsed') === 'true') {
                    sidebar.classList.add('collapsed');
                    if (main) main.classList.add('collapsed');
                    if (logoContainer) logoContainer.classList.add('collapsed');
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-left');
                        toggleIcon.classList.add('fa-bars');
                    }
                }
            }

            // Profile dropdown click toggle
            const profileTrigger = document.getElementById('profile-dropdown-trigger');
            const profileMenu = document.getElementById('profile-dropdown-menu');
            if (profileTrigger && profileMenu) {
                profileTrigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    profileMenu.classList.toggle('opacity-0');
                    profileMenu.classList.toggle('invisible');
                    profileMenu.classList.toggle('opacity-100');
                    profileMenu.classList.toggle('visible');
                });

                document.addEventListener('click', function (e) {
                    if (!profileTrigger.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.classList.add('opacity-0', 'invisible');
                        profileMenu.classList.remove('opacity-100', 'visible');
                    }
                });
            }

            // Expand sidebar if a parent module with submodules is clicked while collapsed
            if (sidebar) {
                sidebar.addEventListener('click', function (e) {
                    const summary = e.target.closest('summary');
                    if (summary && sidebar.classList.contains('collapsed')) {
                        e.preventDefault();
                        const details = summary.closest('details');
                        if (details) {
                            details.open = true;
                        }
                        toggleSidebar();
                    }
                });
            }
        });

        // Close sidebar when clicking outside of it on mobile
        document.addEventListener('click', function (event) {
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

        document.addEventListener('DOMContentLoaded', () => {
            document.body.addEventListener('click', (e) => {
                if (e.target && e.target.tagName === 'INPUT' && e.target.type === 'date') {
                    if (typeof e.target.showPicker === 'function') {
                        e.target.showPicker();
                    }
                }
            }, true);
        });
    </script>