@extends('layouts.main')

@section('title', 'View Role | WorkPilot')

@section('content')

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Role Details</h1>
            <p class="text-sm text-gray-500 mt-1">Viewing permissions for <strong>{{ $role->name }}</strong></p>
        </div>
        <div class="flex items-center gap-3">
            @can('AccessManagement-Edit')
                <a href="{{ route('roles.edit', $role->id) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Role
                </a>
            @endcan
            <a href="{{ route('roles.index') }}"
                class="text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-1 font-medium text-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Roles
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== LEFT: Role Info ===== --}}
        <div class="lg:col-span-1 flex flex-col gap-5">

            {{-- Role Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
                <div
                    class="w-20 h-20 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4 border-2 border-indigo-100">
                    <i class="fa-solid fa-shield-halved text-indigo-500 text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ $role->name }}</h2>

                @if ($role->name === 'Super Admin')
                    <span
                        class="mt-2 text-xs font-bold px-2.5 py-1 rounded-full bg-purple-100 text-purple-600 uppercase tracking-wide">
                        System Role
                    </span>
                @endif

                {{-- Total permissions count --}}
                <div class="mt-4 w-full bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <p class="text-3xl font-bold text-blue-700">
                        {{ $rolePermissions->flatten()->count() }}
                    </p>
                    <p class="text-xs text-blue-500 mt-0.5">Total Permissions</p>
                </div>
            </div>

            {{-- Meta Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Role Info</h3>
                <ul class="flex flex-col gap-3">
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-hashtag text-gray-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Role ID</p>
                            <p class="font-medium text-gray-700">#{{ $role->id }}</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-layer-group text-gray-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Modules Covered</p>
                            <p class="font-medium text-gray-700">{{ $rolePermissions->count() }} modules</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-calendar text-gray-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Created</p>
                            <p class="font-medium text-gray-700">{{ $role->created_at->format('d M, Y') }}</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-clock-rotate-left text-gray-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Last Updated</p>
                            <p class="font-medium text-gray-700">{{ $role->updated_at->format('d M, Y') }}</p>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        {{-- ===== RIGHT: Permissions by Module ===== --}}
        <div class="lg:col-span-2 flex flex-col gap-4">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">
                    <i class="fa-solid fa-key text-indigo-400 mr-2"></i>Assigned Permissions
                </h3>
                <span class="text-xs text-gray-400">Grouped by module</span>
            </div>

            @forelse ($rolePermissions as $module => $perms)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Module Header --}}
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <i class="fa-solid fa-layer-group text-indigo-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">{{ $module }}</span>
                        </div>
                        <span class="text-xs font-semibold bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">
                            {{ $perms->count() }} {{ Str::plural('permission', $perms->count()) }}
                        </span>
                    </div>

                    {{-- Permission Badges --}}
                    <div class="p-5 flex flex-wrap gap-2">
                        @foreach ($perms as $perm)
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs font-semibold">
                                <i class="fa-solid fa-check text-green-500 text-[10px]"></i>
                                {{ explode('-', $perm->name)[1] ?? $perm->name }}
                            </span>
                        @endforeach
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-12 text-center text-gray-400">
                    <i class="fa-solid fa-key text-3xl mb-3 block opacity-30"></i>
                    <p class="text-sm">No permissions assigned to this role.</p>
                    @can('AccessManagement-Edit')
                        <a href="{{ route('roles.edit', $role->id) }}"
                            class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                            <i class="fa-solid fa-plus mr-1"></i> Assign Permissions
                        </a>
                    @endcan
                </div>
            @endforelse

        </div>
    </div>

@endsection
