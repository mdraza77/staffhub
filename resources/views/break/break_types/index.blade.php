@extends('layouts.main')

@section('title', 'Break Types | StaffHub')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Break Types</h1>
                <p class="text-sm text-gray-500">Configure break categories, durations, and icon decorations.</p>
            </div>
            <a href="{{ route('break-types.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-1.5 shadow">
                <i class="fa-solid fa-plus"></i> Add Break Type
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="break-types-table">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                            <th class="px-6 py-3 font-semibold">Icon</th>
                            <th class="px-6 py-3 font-semibold">Name</th>
                            <th class="px-6 py-3 font-semibold text-center">Duration</th>
                            <th class="px-6 py-3 font-semibold text-center">Status</th>
                            <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($breakTypes as $type)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="{{ $type->icon ?? 'fa-solid fa-mug-hot' }} text-base"></i>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ $type->name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="bg-blue-50 text-blue-700 font-semibold px-2.5 py-1 rounded text-xs border border-blue-100">
                                        {{ $type->duration_minutes }} mins
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($type->is_active)
                                        <span
                                            class="bg-green-50 text-green-700 px-2 py-0.5 rounded text-xs border border-green-200 font-semibold">Active</span>
                                    @else
                                        <span
                                            class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs border border-red-200 font-semibold">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('break-types.edit', $type->id) }}"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-base"></i>
                                        </a>
                                        <button type="button"
                                            onclick="confirmDelete('{{ $type->id }}', '{{ addslashes($type->name) }}')"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Delete">
                                            <i class="fa-solid fa-trash text-base"></i>
                                        </button>
                                        <form id="delete-form-{{ $type->id }}"
                                            action="{{ route('break-types.destroy', $type->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Delete break type '" + name + "'? Historical records might be affected.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush