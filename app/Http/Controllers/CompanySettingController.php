<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Company-Index', only: ['edit']),
            new Middleware('permission:Company-Edit', only: ['update']),
        ];
    }

    public function edit()
    {
        $setting = CompanySetting::first();
        if (!$setting) {
            $setting = CompanySetting::create([
                'name' => 'StaffHub',
                'email' => 'info@staffhub.com',
                'phone_number' => '1234567890',
                'address' => 'Company Address',
                'state' => 'State',
                'city' => 'City',
                'country' => 'Country',
                'pincode' => '123456',
                'gst_no' => 'GSTIN123456',
                'pan' => 'PAN123456',
                'bank_name' => 'Bank Name',
                'ac_number' => '000000000000',
                'ifsc_code' => 'IFSC0000000',
                'branch' => 'Branch Name',
                'logo' => null,
            ]);
        }

        return view('company.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'country' => 'required',
            'pincode' => 'required',
            'gst_no' => 'required',
            'pan' => 'required',
            'bank_name' => 'required',
            'ac_number' => 'required',
            'ifsc_code' => 'required',
            'branch' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $setting = CompanySetting::first();
        if (!$setting) {
            $setting = new CompanySetting();
        }

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('company', 'public');
        }

        $setting->fill($data)->save();

        return redirect()->back()->with('success', 'Company settings updated successfully.');
    }
}
