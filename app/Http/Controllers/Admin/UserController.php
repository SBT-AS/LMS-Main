<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->select(['id', 'name', 'email']);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('roles', function ($user) {
                    return $user->getRoleNames()->implode(', ');
                })
                ->addColumn('action', function ($data) {
                    return view('backend.layouts.action', compact('data'))
                        ->with('module', 'users')
                        ->with('module2', 'admin.users');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.master.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('backend.master.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required'   
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->roles);

        return response()->json([
            'success' => 'User Created Successfully',
            'url'     => route('admin.users.index'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        
        return view('backend.master.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = $request->except('password');    
        }

        $user = User::findOrFail($id);
        $user->update($input);
        
        $user->syncRoles($request->roles);

        return response()->json([
            'success' => 'User Updated Successfully',
            'url'     => route('admin.users.index'),
        ]);
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('backend.master.users.show', compact('user'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json([
            'success' => 'User deleted successfully.',
        ]);
    }

}
