@extends('layouts.main')

@section('title', 'Leave Types | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Leave Types</h1>
            <p class="text-sm text-gray-500 mt-1">Manage different categories of leaves (e.g., Sick, Casual).</p>
        </div>

        @can('LeaveType-Create')
            <button onclick="toggleModal('addLeaveTypeModal')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Add Leave Type
            </button>
        @endcan
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold w-16">#</th>
                        <th class="px-6 py-3 font-semibold">Leave Type Name</th>
                        <th class="px-6 py-3 font-semibold text-center">Days Allowed (Yearly)</th>
                        <th class="px-6 py-3 font-semibold text-center">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($leaveTypes as $key => $type)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $type->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 text-center font-medium">{{ $type->days_allowed }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($type->is_active)
                                    <span
                                        class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Active</span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @can('LeaveType-Edit')
                                    <button onclick="toggleModal('editLeaveTypeModal{{ $type->id }}')"
                                        class="text-blue-600 hover:text-blue-800 mx-2 transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                @endcan

                                @can('LeaveType-Delete')
                                    <button type="button" onclick="confirmDelete({{ $type->id }})"
                                        class="text-red-600 hover:text-red-800 mx-2 transition-colors" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endcan

                                <form id="delete-form-{{ $type->id }}"
                                    action="{{ route('leave-types.destroy', $type->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <div id="editLeaveTypeModal{{ $type->id }}"
                            class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center transition-opacity">
                            <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden">
                                <div
                                    class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-lg font-bold text-gray-800">Edit Leave Type</h3>
                                    <button type="button" onclick="toggleModal('editLeaveTypeModal{{ $type->id }}')"
                                        class="text-gray-400 hover:text-gray-600">
                                        <i class="fa-solid fa-xmark text-xl"></i>
                                    </button>
                                </div>
                                <form action="{{ route('leave-types.update', $type->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-6 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type Name
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="name" value="{{ $type->name }}"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Days Allowed (Per
                                                Year) <span class="text-red-500">*</span></label>
                                            <input type="number" name="days_allowed" value="{{ $type->days_allowed }}"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                                min="0" required>
                                        </div>
                                        <div class="flex items-center gap-2 mt-4">
                                            <input type="checkbox" name="is_active" id="is_active_{{ $type->id }}"
                                                value="1" {{ $type->is_active ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="is_active_{{ $type->id }}"
                                                class="text-sm font-medium text-gray-700">Active (Employees can apply for
                                                this)</label>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                                        <button type="button"
                                            onclick="toggleModal('editLeaveTypeModal{{ $type->id }}')"
                                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No leave types found. Create one to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="addLeaveTypeModal"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center transition-opacity">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Add New Leave Type</h3>
                <button type="button" onclick="toggleModal('addLeaveTypeModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('leave-types.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="e.g. Sick Leave, Casual Leave"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Days Allowed (Per Year) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="days_allowed" placeholder="e.g. 12"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                            min="0" required>
                    </div>
                    <div class="flex items-center gap-2 mt-4">
                        <input type="checkbox" name="is_active" id="is_active_new" value="1" checked
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="is_active_new" class="text-sm font-medium text-gray-700">Active (Employees can apply
                            for this)</label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('addLeaveTypeModal')"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('styles')
    <script>
        // Simple Vanilla JS to toggle Tailwind Modals
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
        }

        // SweetAlert for Delete Confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This leave type will be deleted permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
