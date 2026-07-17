@extends('layouts.main')

@section('title', 'Edit Task Details | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Task Details</h1>
            <p class="text-sm text-gray-500 mt-1">Update task properties and descriptions.</p>
        </div>
        <x-back-button :url="route('tasks.index')" label="Back to Tasks" />
    </div>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                <select name="project_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white">
                    <option value="">Select Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"
                            {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}{{ $project->trashed() ? ' [Deleted]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Task Title <span
                        class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}"
                    placeholder="e.g., Design Login Page UI"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign To <span
                        class="text-red-500">*</span></label>
                <select name="assigned_to"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white"
                    required>
                    <option value="">Select an Employee</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}"
                            {{ old('assigned_to', $task->assigned_to) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} ({{ $employee->designation ?? 'Employee' }}){{ $employee->trashed() ? ' [Deleted]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Tester</label>
                <select name="tester_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white">
                    <option value="">Select a Tester</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}"
                            {{ old('tester_id', $task->tester_id) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} ({{ $employee->designation ?? 'Employee' }}){{ $employee->trashed() ? ' [Deleted]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority <span
                        class="text-red-500">*</span></label>
                <select name="priority"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white"
                    required>
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium
                    </option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('priority', $task->priority) == 'critical' ? 'selected' : '' }}>
                        Critical</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Status <span
                        class="text-red-500">*</span></label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white"
                    required>
                    <option value="open" {{ old('status', $task->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="testing" {{ old('status', $task->status) == 'testing' ? 'selected' : '' }}>Testing</option>
                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="closed" {{ old('status', $task->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline <span
                        class="text-red-500">*</span></label>
                <input type="date" name="deadline"
                    value="{{ old('deadline', \Carbon\Carbon::parse($task->deadline)->format('Y-m-d')) }}"
                    min="{{ now()->format('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                    required>
            </div>

            <div class="md:col-span-2 mt-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Detailed Instructions</label>
                <textarea name="description" id="task-editor" placeholder="Write detailed instructions here...">{{ old('description', $task->description) }}</textarea>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
            {{-- <a href="{{ route('tasks.index') }}"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</a> --}}
            @can('Task-Edit')
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            @endcan
        </div>
    </form>
@endsection

@push('styles')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#task-editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                        'blockQuote'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>

    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
            border-bottom-left-radius: 0.5rem !important;
            border-bottom-right-radius: 0.5rem !important;
        }

        .ck-toolbar {
            border-top-left-radius: 0.5rem !important;
            border-top-right-radius: 0.5rem !important;
            background-color: #f9fafb !important;
        }
    </style>
@endpush
