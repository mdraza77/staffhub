<?php

namespace App\Http\Controllers;

use App\Models\BreakType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BreakTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:BreakType-Manage'),
        ];
    }

    public function index()
    {
        $breakTypes = BreakType::orderBy('name')->get();
        return view('break.break_types.index', compact('breakTypes'));
    }

    public function create()
    {
        return view('break.break_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:break_types',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        BreakType::create([
            'name' => $request->name,
            'duration_minutes' => $request->duration_minutes,
            'icon' => $request->icon ?? 'fa-solid fa-mug-hot',
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('break-types.index')->with('success', 'Break Type created successfully.');
    }

    public function edit(BreakType $breakType)
    {
        return view('break.break_types.edit', compact('breakType'));
    }

    public function update(Request $request, BreakType $breakType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:break_types,name,' . $breakType->id,
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $breakType->update([
            'name' => $request->name,
            'duration_minutes' => $request->duration_minutes,
            'icon' => $request->icon ?? 'fa-solid fa-mug-hot',
            'is_active' => (bool)$request->is_active,
        ]);

        return redirect()->route('break-types.index')->with('success', 'Break Type updated successfully.');
    }

    public function destroy(BreakType $breakType)
    {
        $breakType->delete();
        return redirect()->route('break-types.index')->with('success', 'Break Type deleted successfully.');
    }
}
