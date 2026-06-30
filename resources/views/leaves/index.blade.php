@extends('layouts.main')

@section('title', 'Leave Requests | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Leave Requests</h1>
            <p class="text-sm text-gray-500 mt-1">Apply for leave and manage employee requests.</p>
        </div>

        <button onclick="toggleModal('applyLeaveModal')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-paper-plane"></i> Apply Leave
        </button>
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
                        <th class="px-6 py-3 font-semibold">Employee</th>
                        <th class="px-6 py-3 font-semibold">Leave Type</th>
                        <th class="px-6 py-3 font-semibold">Duration</th>
                        <th class="px-6 py-3 font-semibold">Reason</th>
                        <th class="px-6 py-3 font-semibold text-center">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-800">{{ $leave->user->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded border border-blue-100">
                                    {{ $leave->leaveType->name }}
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
                                    <span
                                        class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">Rejected</span>
                                @else
                                    <span
                                        class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($leave->status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
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
                                            onsubmit="return confirm('Are you sure you want to reject this leave?');">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded transition-colors"
                                                title="Reject">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <i class="fa-regular fa-folder-open text-3xl text-gray-300 mb-2 block"></i>
                                No leave requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="applyLeaveModal"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center transition-opacity">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 overflow-hidden">
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
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="end_date"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason <span
                                class="text-red-500">*</span></label>
                        <textarea name="reason" rows="3" placeholder="Briefly explain why you need this leave..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500" required></textarea>
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

@push('styles')
    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
        }
    </script>
@endpush
