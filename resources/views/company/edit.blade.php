@extends('layouts.main')

@section('title', 'Company Settings | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Company Settings</h1>
            <p class="text-sm text-gray-500 mt-1">Configure company profile, contact details, billing credentials, and
                banking info.</p>
        </div>
        <a href="{{ route('dashboard') }}"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
            Back to Dashboard
        </a>
    </div>

    <form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== LEFT: Company Logo Card ===== --}}
            <div class="lg:col-span-1 flex flex-col gap-6">
                <div
                    class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col items-center text-center">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4 align-self-start">Company
                        Logo</h3>

                    {{-- Logo Preview --}}
                    <div class="mb-6 relative group">
                        @if ($setting->logo)
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->name }}" id="logo-preview"
                                class="w-32 h-32 rounded-xl object-contain border border-gray-200 p-2 shadow-sm">
                        @else
                            <div id="logo-placeholder"
                                class="w-32 h-32 rounded-xl bg-blue-50 flex flex-col items-center justify-center border border-dashed border-blue-200 shadow-sm p-3">
                                <i class="fa-solid fa-building text-blue-400 text-3xl mb-1.5"></i>
                                <span class="text-[10px] text-blue-500 font-medium">No Logo Uploaded</span>
                            </div>
                            <img id="logo-preview"
                                class="w-32 h-32 rounded-xl object-contain border border-gray-200 p-2 shadow-sm hidden" src=""
                                alt="Preview">
                        @endif
                    </div>

                    {{-- Image File Input --}}
                    <div class="w-full">
                        <label
                            class="block mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wide text-left">Upload
                            New Logo</label>
                        <input type="file" name="logo" id="logo-input" accept="image/"
                            class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors border border-gray-200 rounded-lg p-1"
                            onchange="previewLogo(this)">
                        <p class="text-[10px] text-gray-400 mt-2 text-left">Supports JPEG, PNG, JPG, GIF, SVG, WEBP. Max
                            size: 2MB</p>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: Form Details ===== --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- Profile Section --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <div class="border-b border-gray-100 pb-3 mb-5">
                        <h2 class="text-md font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-address-card text-blue-500"></i>
                            General & Address Details
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Company
                                Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $setting->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Company
                                Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $setting->email) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Phone
                                Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $setting->phone_number) }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Pincode
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="pincode" value="{{ old('pincode', $setting->pincode) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Full
                                Address <span class="text-red-500">*</span></label>
                            <textarea name="address" rows="3" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('address', $setting->address) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">City
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="city" value="{{ old('city', $setting->city) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">State
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="state" value="{{ old('state', $setting->state) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Country
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="country" value="{{ old('country', $setting->country) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Taxation & Finance Section --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <div class="border-b border-gray-100 pb-3 mb-5">
                        <h2 class="text-md font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-blue-500"></i>
                            Taxation & Billing Details
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">GST Number
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="gst_no" value="{{ old('gst_no', $setting->gst_no) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">PAN
                                Number
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="pan" value="{{ old('pan', $setting->pan) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Banking details --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <div class="border-b border-gray-100 pb-3 mb-5">
                        <h2 class="text-md font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-building-columns text-blue-500"></i>
                            Banking Details
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Bank Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $setting->bank_name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Account
                                Number <span class="text-red-500">*</span></label>
                            <input type="text" name="ac_number" value="{{ old('ac_number', $setting->ac_number) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">IFSC Code
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $setting->ifsc_code) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Branch
                                Name <span class="text-red-500">*</span></label>
                            <input type="text" name="branch" value="{{ old('branch', $setting->branch) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-150">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                        Cancel
                    </a>
                    @can('Company-Edit')
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm text-sm">
                            Save Settings
                        </button>
                    @endcan
                </div>

            </div>

        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function previewLogo(input) {
            const preview = document.getElementById('logo-preview');
            const placeholder = document.getElementById('logo-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush