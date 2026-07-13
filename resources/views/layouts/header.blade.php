<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

<body class="bg-gray-50 text-gray-800 font-sans antialiased transition-all duration-300">

    <header id="header"
        class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm h-16 flex items-center px-4 md:px-6 transition-all duration-300">

        <div class="flex items-center justify-between w-auto md:w-64 gap-4 md:gap-0">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 text-xl font-bold text-blue-700 decoration-none">
                <img class="w-16 h-16"
                    src="{{ !empty($globalSetting->logo) ? asset($globalSetting->logo) : asset('img/logo.png') }}"
                    alt="{{ $globalSetting->name ?? 'StaffHub Logo' }}" class="max-h-8">
            </a>
            <i class="bi bi-list text-2xl cursor-pointer text-gray-800 hover:text-blue-600 transition-colors toggle-sidebar-btn"
                onclick="toggleSidebar()"></i>
        </div>

        <nav class="ml-auto flex items-center">
            <ul class="flex items-center m-0 p-0 list-none">

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
                        <i class="fa-solid fa-dashboard text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endcan

            {{-- ===== EMPLOYEE MANAGEMENT ===== --}}
            @php
                $employeeActive =
                    request()->routeIs('employees.index') ||
                    request()->routeIs('employees.create') ||
                    request()->routeIs('employees.edit') ||
                    request()->routeIs('employees.show');
            @endphp

            @if (auth()->check() && (auth()->user()->can('Employee-Index') || auth()->user()->can('Employee-Create')))
                <li title="Employee Management">
                    <details class="group" {{ $employeeActive ? 'open' : '' }}>
                        <summary
                            class="{{ $employeeActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-users text-lg"></i>
                                <span>Employee Mgmt</span>
                            </div>
                            <i
                                class="bi bi-chevron-down transition-transform duration-300 {{ $employeeActive ? 'rotate-180' : 'group-open:-rotate-180' }}"></i>
                        </summary>
                        <ul class="pl-9 pr-2 py-2 space-y-1">
                            @can('Employee-Index')
                                <li>
                                    <a href="{{ route('employees.index') }}"
                                        class="{{ request()->routeIs('employees.index') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>All Employees</span>
                                    </a>
                                </li>
                            @endcan
                            @can('Employee-Create')
                                <li>
                                    <a href="{{ route('employees.create') }}"
                                        class="{{ request()->routeIs('employees.create') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>Add Employee</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif

            {{-- ===== DEPARTMENTS ===== --}}
            @can('Department-Index')
                <li>
                    <a href="{{ route('departments.index') }}"
                        class="{{ request()->routeIs('departments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-building text-lg"></i>
                        <span>Departments</span>
                    </a>
                </li>
            @endcan

            {{-- ===== ATTENDANCE ===== --}}
            @can('Attendance-Index')
                <li>
                    <a href="{{ route('attendance.index') }}"
                        class="{{ request()->routeIs('attendance.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-calendar-check text-lg"></i>
                        <span>Attendance</span>
                    </a>
                </li>
            @endcan

            {{-- ===== LEAVE TYPES ===== --}}
            @can('LeaveType-Index')
                <li>
                    <a href="{{ route('leave-types.index') }}"
                        class="{{ request()->routeIs('leave-types.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-plane-departure text-lg"></i>
                        <span>Leave Types</span>
                    </a>
                </li>
            @endcan

            {{-- ===== LEAVES ===== --}}
            @can('Leave-Index')
                <li>
                    <a href="{{ route('leaves.index') }}"
                        class="{{ request()->routeIs('leaves.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-person-walking-arrow-right text-lg"></i>
                        <span>Leaves</span>
                    </a>
                </li>
            @endcan

            {{-- ===== HOLIDAYS ===== --}}
            @can('Holiday-Index')
                <li>
                    <a href="{{ route('holidays.index') }}"
                        class="{{ request()->routeIs('holidays.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-umbrella-beach text-lg"></i>
                        <span>Holidays</span>
                    </a>
                </li>
            @endcan

            {{-- ===== ANNOUNCEMENTS ===== --}}
            @can('Announcement-Index')
                <li>
                    <a href="{{ route('announcements.index') }}"
                        class="{{ request()->routeIs('announcements.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-bullhorn text-lg"></i>
                        <span>Announcements</span>
                    </a>
                </li>
            @endcan

            {{-- ===== PAYROLL MANAGEMENT ===== --}}
            @php
                $payrollActive = request()->routeIs('payroll.*');
            @endphp
            @if (auth()->check() && (auth()->user()->can('Salary-View') || auth()->user()->can('Payslip-Index')))
                <li title="Payroll Management">
                    <details class="group" {{ $payrollActive ? 'open' : '' }}>
                        <summary
                            class="{{ $payrollActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
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
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>Salary Structures</span>
                                    </a>
                                </li>
                            @endcan
                            @can('Payslip-Index')
                                <li>
                                    <a href="{{ route('payroll.payslips.index') }}"
                                        class="{{ request()->routeIs('payroll.payslips.*') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>Monthly Payslips</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif

            {{-- ===== TASKS ===== --}}
            @can('Task-Index')
                <li>
                    <a href="{{ route('tasks.index') }}"
                        class="{{ request()->routeIs('tasks.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-list-check text-lg"></i>
                        <span>Tasks</span>
                    </a>
                </li>
            @endcan

            {{-- ===== DEFECTS ===== --}}
            @can('Defect-Index')
                <li>
                    <a href="{{ route('defects.index') }}"
                        class="{{ request()->routeIs('defects.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-bug text-lg"></i>
                        <span>Defects</span>
                    </a>
                </li>
            @endcan

            {{-- ===== BREAKS MANAGEMENT ===== --}}
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
                                <i class="fa-solid fa-mug-hot text-lg"></i>
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
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>Enter Break Room</span>
                                    </a>
                                </li>
                            @endcan
                            @can('Break-History-View')
                                <li>
                                    <a href="{{ route('breaks.history') }}"
                                        class="{{ request()->routeIs('breaks.history') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>Break History</span>
                                    </a>
                                </li>
                            @endcan
                            @can('BreakType-Manage')
                                <li>
                                    <a href="{{ route('break-types.index') }}"
                                        class="{{ request()->routeIs('break-types.*') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 transition-colors">
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>Break Types</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif

            {{-- ===== ROLES & PERMISSIONS ===== --}}
            @can('AccessManagement-Index')
                <li>
                    <a href="{{ route('roles.index') }}"
                        class="{{ request()->routeIs('roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                        <span>Roles & Permissions</span>
                    </a>
                </li>
            @endcan

            {{-- ===== REPORTS MANAGEMENT ===== --}}
            @php
                $reportsActive =
                    request()->routeIs('reports.employees') ||
                    request()->routeIs('reports.attendance') ||
                    request()->routeIs('reports.leaves') ||
                    request()->routeIs('reports.departments') ||
                    request()->routeIs('reports.leave-types');
            @endphp

            @if (auth()->check() && (auth()->user()->can('Employee-Index') || auth()->user()->can('Employee-Create')))
                <li>
                    <details class="group" {{ $reportsActive ? 'open' : '' }}>
                        <summary
                            class="{{ $reportsActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-3">
                                <i class="fa fa-bar-chart" aria-hidden="true"></i>
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
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>Employees</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif

            {{-- ===== SETTINGS ===== --}}
            {{-- @php
                $settingsActive = request()->routeIs('settings.*') || request()->routeIs('company.*');
            @endphp

            @if (auth()->check() && (auth()->user()->can('Company-Index') || auth()->user()->can('Settings-Index')))
                <li>
                    <details class="group" {{ $settingsActive ? 'open' : '' }}>
                        <summary
                            class="{{ $settingsActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }} flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors cursor-pointer list-none font-medium [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-gear text-lg"></i>
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
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>Company Setting</span>
                                    </a>
                                </li>
                            @endcan
                            @can('Settings-Index')
                                <li>
                                    <a href="{{ route('settings.index') }}"
                                        class="{{ request()->routeIs('settings.index') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} flex items-center gap-2 text-sm py-1.5 tran sition-colors">
                                        <i class="bi bi-circle text-[8px]"></i>
                                        <span>General Settings</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endif --}}

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

            sidebar.classList.toggle('-translate-x-full'); // Hides/Shows sidebar on mobile
            sidebar.classList.toggle('md:translate-x-0'); // Toggle state on desktop

            // Adjust main content margin based on screen size
            if (window.innerWidth >= 768) { // md breakpoint in tailwind
                if (main.classList.contains('md:ml-64')) {
                    main.classList.remove('md:ml-64');
                    main.classList.add('ml-0');
                } else {
                    main.classList.remove('ml-0');
                    main.classList.add('md:ml-64');
                }
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
    </script>
