@extends('layouts.main')

@section('title', 'Assign New Task | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Assign New Task</h1>
            <p class="text-sm text-gray-500 mt-1">Create and assign a task to your team members.</p>
        </div>
        <a href="{{ route('tasks.index') }}"
            class="text-gray-600 hover:text-indigo-600 transition-colors flex items-center gap-1 font-medium">
            <i class="fa-solid fa-arrow-left"></i> Back to Tasks
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                <input type="text" name="project_name" value="{{ old('project_name') }}"
                    placeholder="e.g., Steel Pvt. Ltd."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Task Title <span
                        class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g., Design Login Page UI"
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
                        <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} ({{ $employee->designation ?? 'Employee' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline <span
                        class="text-red-500">*</span></label>
                <input type="date" name="deadline" value="{{ old('deadline') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                    required>
            </div>

            <div class="md:col-span-2 mt-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Detailed Instructions <span
                        class="text-red-500">*</span></label>
                <textarea name="description" id="task-editor" placeholder="Write detailed instructions here...">{{ old('description') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Media / Reference Links</label>
                <input type="text" name="media_links" value="{{ old('media_links') }}"
                    placeholder="e.g., Google Drive link, Figma URL, GitHub Repo"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                <p class="text-xs text-gray-500 mt-1">Paste external URLs separated by commas if multiple.</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Manager Remark (Optional)</label>
                <textarea name="manager_remark" rows="2" placeholder="Any initial remarks or priority notes..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">{{ old('manager_remark') }}</textarea>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('tasks.index') }}"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</a>
            @can('Task-Create')
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Assign Task
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
        /* Tailwind Fixes for CKEditor Height and UI */
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
