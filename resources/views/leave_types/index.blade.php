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
                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <i class="fa-solid fa-plus"></i> Add Leave Type
            </button>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="leave_types" class="w-full text-left border-collapse">
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
                    @foreach ($leaveTypes as $key => $type)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $type->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 text-center font-medium">{{ $type->days_allowed }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-badge :value="$type->is_active ? 'active' : 'inactive'" />
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($type->trashed())
                                    {{-- @can('LeaveType-Restore') --}}
                                        <button type="button" onclick="confirmRestore({{ $type->id }})"
                                            class="text-green-600 hover:text-green-800 mx-2 transition-colors" title="Restore">
                                            <i class="fa-solid fa-rotate-left text-base"></i>
                                        </button>
                                    {{-- @endcan --}}

                                    <form id="restore-form-{{ $type->id }}"
                                        action="{{ route('leave-types.restore', $type->id) }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                @else
                                    @can('LeaveType-Edit')
                                        <button onclick="toggleModal('editLeaveTypeModal{{ $type->id }}')"
                                            class="text-blue-600 hover:text-blue-800 mx-2 transition-colors" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    @endcan

                                    @can('LeaveType-Delete')
                                        <button type="button" onclick="confirmDelete({{ $type->id }})"
                                            class="text-red-600 hover:text-red-800 mx-2 transition-colors" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    @endcan

                                    <form id="delete-form-{{ $type->id }}"
                                        action="{{ route('leave-types.destroy', $type->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <div id="editLeaveTypeModal{{ $type->id }}"
                            class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center opacity-0 invisible pointer-events-none transition-all duration-300 ease-out">
                            <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden transform scale-95 opacity-0 -translate-y-4 transition-all duration-300 ease-out">
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="addLeaveTypeModal"
        class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center opacity-0 invisible pointer-events-none transition-all duration-300 ease-out">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden transform scale-95 opacity-0 -translate-y-4 transition-all duration-300 ease-out">
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

@push('scripts')
    <script>
        // Simple Vanilla JS to toggle Tailwind Modals with transitions
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal) {
                const isOpen = !modal.classList.contains('invisible');
                if (isOpen) {
                    closeModal(modal);
                } else {
                    openModal(modal);
                }
            }
        }

        function openModal(modal) {
            modal.classList.remove('invisible', 'opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
            
            const content = modal.querySelector('.bg-white');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0', '-translate-y-4');
                content.classList.add('scale-100', 'opacity-100', 'translate-y-0');
            }
        }

        function closeModal(modal) {
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            modal.classList.add('opacity-0', 'pointer-events-none');
            
            const content = modal.querySelector('.bg-white');
            if (content) {
                content.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
                content.classList.add('scale-95', 'opacity-0', '-translate-y-4');
            }
            
            setTimeout(() => {
                if (modal.classList.contains('opacity-0')) {
                    modal.classList.add('invisible');
                }
            }, 300);
        }

        // Close modal when clicking outside (on the backdrop overlay)
        window.addEventListener('click', function (e) {
            const modalOverlays = document.querySelectorAll('.fixed.inset-0.z-50');
            modalOverlays.forEach(modal => {
                if (e.target === modal) {
                    closeModal(modal);
                }
            });
        });

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

        function confirmRestore(leaveTypeId) {
                Swal.fire({
                    title: "Restore Leave Type?",
                    text: "This leave type will be restored to active status.",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#10b981",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, restore it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('restore-form-' + leaveTypeId).submit();
                    }
                });
            }
    </script>
@endpush
