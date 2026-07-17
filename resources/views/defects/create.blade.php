@extends('layouts.main')

@section('title', 'Report Bug | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Report a Bug</h1>
            <p class="text-sm text-gray-500 mt-1">Log defect findings with reproducer details and parameters.</p>
        </div>
        <x-back-button :url="route('defects.index')" label="Back to Defects" />
    </div>

    <form action="{{ route('defects.store') }}" method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2">
                <h3 class="text-lg font-semibold text-gray-800">Defect Overview</h3>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bug Summary / Title <span
                        class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                    placeholder="e.g., App crashes when clicking checkout button"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                <select name="project_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white">
                    <option value="">Select a Project (Optional)</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}{{ $project->trashed() ? ' [Deleted]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Module <span
                        class="text-red-500">*</span></label>
                <input type="text" name="module" value="{{ old('module') }}" placeholder="e.g., Authentication, Payments"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                    required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sub-Module</label>
                <input type="text" name="sub_module" value="{{ old('sub_module') }}"
                    placeholder="e.g., OTP Verification, Stripe"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Environment <span
                        class="text-red-500">*</span></label>
                <select name="environment"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white"
                    required>
                    <option value="production" {{ old('environment') === 'production' ? 'selected' : '' }}>Production
                    </option>
                    <option value="staging" {{ old('environment') === 'staging' ? 'selected' : '' }}>Staging</option>
                    <option value="testing" {{ old('environment') === 'testing' ? 'selected' : '' }}>Testing / QA</option>
                    <option value="local" {{ old('environment') === 'local' ? 'selected' : '' }}>Local / Dev</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Browser / OS Details</label>
                <input type="text" name="browser_os" value="{{ old('browser_os') }}"
                    placeholder="e.g., Chrome 120 / Windows 11"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
            </div>

            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2 mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Priority & Assignment</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Severity <span
                        class="text-red-500">*</span></label>
                <select name="severity"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white"
                    required>
                    <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('severity', 'high') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority <span
                        class="text-red-500">*</span></label>
                <select name="priority"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white"
                    required>
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Assignee (Engineer)</label>
                <select name="assigned_to"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white">
                    <option value="">Select Assignee</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} ({{ $employee->designation ?? 'Developer' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2 mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Description & Reproduction Steps</h3>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Defect Description <span
                        class="text-red-500">*</span></label>
                <textarea name="description" id="defect-description-editor"
                    placeholder="Write a detailed summary of the bug..."></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Steps to Reproduce</label>
                <textarea name="steps_to_reproduce" id="defect-repro-editor"
                    placeholder="e.g. 1. Go to Login page... 2. Press submit..."></textarea>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
            {{-- <a href="{{ route('defects.index') }}"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</a>
            --}}
            <button type="submit"
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium shadow-sm">Report
                Defect</button>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- CKEditor Library --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    {{-- ClassicEditor init --}}
    <script>
        $(document).ready(function () {
            ClassicEditor
                .create(document.querySelector('#defect-description-editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                        'blockQuote'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#defect-repro-editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                        'blockQuote'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endpush