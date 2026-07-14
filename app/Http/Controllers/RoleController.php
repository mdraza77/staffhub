<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:AccessManagement-Index', only: ['index']),
            new Middleware('permission:AccessManagement-Create', only: ['create', 'store']),
            new Middleware('permission:AccessManagement-Edit', only: ['edit', 'update']),
            new Middleware('permission:AccessManagement-Delete', only: ['destroy']),
            new Middleware('permission:AccessManagement-View', only: ['show']),
        ];
    }

    // ===================== INDEX =====================
    public function index(): View
    {
        $roles = Role::withCount('permissions')->orderByDesc('id')->get();
        return view('roles.index', compact('roles'));
    }

    // ===================== CREATE =====================
    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($perm) {
            return explode('-', $perm->name)[0];
        });

        return view('roles.create', compact('permissions'));
    }

    // ===================== STORE =====================
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'permission' => 'required|array|min:1',
            'permission.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'permission.required' => 'Please select at least one permission.',
            'permission.min' => 'Please select at least one permission.',
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create(['name' => $request->name]);

            $role->syncPermissions($request->permission);

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', "Role \"{$role->name}\" created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role Creation Failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the role. Please try again.');
        }
    }

    // ===================== SHOW =====================
    public function show($id): View
    {
        $role = Role::findOrFail($id);

        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_has_permissions.role_id', $id)
            ->orderBy('permissions.name')
            ->get()
            ->groupBy(function ($perm) {
                return explode('-', $perm->name)[0];
            });

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    // ===================== EDIT =====================
    public function edit($id): View
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::orderBy('name')->get()->groupBy(function ($perm) {
            return explode('-', $perm->name)[0];
        });

        // Permission NAMES collect karo — IDs nahi
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // ===================== UPDATE =====================
    public function update(Request $request, $id): RedirectResponse
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Super Admin role cannot be modified.');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $id,
            'permission' => 'required|array|min:1',
            'permission.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'permission.required' => 'Please select at least one permission.',
            'permission.min' => 'Please select at least one permission.',
        ]);

        try {
            DB::beginTransaction();

            $role->update(['name' => $request->name]);

            // Permission NAMES pass karo
            $role->syncPermissions($request->permission);

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', "Role \"{$role->name}\" updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role Update Failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the role. Please try again.');
        }
    }

    // ===================== DESTROY =====================
    public function destroy($id): RedirectResponse
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Super Admin role cannot be deleted.');
        }

        try {
            DB::beginTransaction();

            $roleName = $role->name;
            $role->delete();

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', "Role \"{$roleName}\" deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role Deletion Failed: ' . $e->getMessage());

            return redirect()
                ->route('roles.index')
                ->with('error', 'Something went wrong while deleting the role. Please try again.');
        }
    }
}
