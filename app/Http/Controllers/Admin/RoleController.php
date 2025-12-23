<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Role::select(['id', 'name']);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('backend.layouts.action', compact('data'))
                        ->with('module', 'roles')
                        ->with('module2', 'admin.roles');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.master.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.master.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'success' => 'Role Added Successfully',
            'url'     => route('admin.roles.index'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('backend.master.roles.view', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('backend.master.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => 'Role Updated Successfully',
            'url'     => route('admin.roles.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'success' => 'Role deleted successfully.',
        ]);
    }
    public function permissions(Role $role)
    {
        $permissions = Permission::all();
        $selectedPermissions = $role->permissions->pluck('name')->toArray();
        return view('backend.master.roles.permission', compact('role', 'permissions', 'selectedPermissions'));
    }


    public function permissionsStore(Role $role, Request $request)
    {
        $role->syncPermissions($request->permissions);
        return response()->json([
            'success' => 'Role Permissions Updated Successfully!',
            'url' => route('admin.roles.index'),
        ]);
    }
    
}
