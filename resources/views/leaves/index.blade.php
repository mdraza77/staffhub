@extends('layouts.main')

@section('title', 'Leave Requests | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Leave Requests</h1>
            <p class="text-sm text-gray-500 mt-1">Apply for leave and manage employee requests.</p>
        </div>

        @can('Leave-Apply')
            <button onclick="toggleModal('applyLeaveModal')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-paper-plane"></i> Apply Leave
            </button>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="leaves" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">Employee</th>
                        <th class="px-6 py-3 font-semibold">Leave Type</th>
                        <th class="px-6 py-3 font-semibold">Duration</th>
                        <th class="px-6 py-3 font-semibold">Reason</th>
                        <th class="px-6 py-3 font-semibold text-center">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($leaves as $leave)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-800">{{ $leave->user->name ?? 'User Deleted' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded border border-blue-100">
                                    {{ $leave->leaveType->name ?? 'Leave Type Deleted' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div><span class="font-semibold text-gray-500 text-xs">From:</span>
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d M, Y') }}</div>
                                <div><span class="font-semibold text-gray-500 text-xs">To:</span>
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d M, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ Str::limit($leave->reason, 40) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($leave->status === 'approved')
                                    <span
                                        class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Approved</span>
                                @elseif($leave->status === 'rejected')
                                    <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">Rejected</span>
                                @else
                                    <span
                                        class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($leave->status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        @can('Leave-ApproveReject')
                                            <form action="{{ route('leaves.updateStatus', $leave->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"
                                                    class="text-green-600 hover:text-green-800 bg-green-50 hover:bg-green-100 p-2 rounded transition-colors"
                                                    title="Approve">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('leaves.updateStatus', $leave->id) }}" method="POST"
                                                onclick="confirmDelete(event)" id="reject-leave-form">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded transition-colors"
                                                    title="Reject">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="applyLeaveModal"
        class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center opacity-0 invisible pointer-events-none transition-all duration-300 ease-out">
        <div
            class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 overflow-hidden transform scale-95 opacity-0 -translate-y-4 transition-all duration-300 ease-out">
            <div class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Apply for Leave</h3>
                <button type="button" onclick="toggleModal('applyLeaveModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type <span
                                class="text-red-500">*</span></label>
                        <select name="leave_type_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                            required>
                            <option value="">Select leave type</option>
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->days_allowed }}
                                    days/yr)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="start_date"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                min="{{ now()->format('Y-m-d') }}" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="end_date"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ now()->addDays(2)->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason <span
                                class="text-red-500">*</span></label>
                        <textarea name="reason" rows="3" placeholder="Briefly explain why you need this leave..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                            required></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('applyLeaveModal')"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm">Submit
                        Request</button>
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
    </script>

    <script>
        function confirmDelete(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You cannot revert this! This will be marked as Rejected.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // bg-red-500
                cancelButtonColor: '#4b5563', // bg-gray-600
                confirmButtonText: 'Yes, reject leave!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reject-leave-form').submit();
                }
            });
        }
    </script>
@endpush