<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function index()
    {
        /*
        $role1 = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role2 = Role::create(['name' => 'author', 'guard_name' => 'web']);
        $role3 = Role::create(['name' => 'translation_editor', 'guard_name' => 'web']);
        */
        /*
        $role = Role::create(['name' => 'writer3']);
        $permission = Permission::create(['name' => 'test3']);
        
        $permission->assignRole($role);
        $user->assignRole($request->role);
        */
        $users = User::with('roles')->paginate(10);

        return response()->json($users);
    }
    public function create()
    {
        
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'status' => ['required', 'in:active,blocked'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,author,translation_editor'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;
        $user->password = bcrypt($request->password);
        $user->save();
        $user->assignRole($request->role);
        $user = User::with('roles')->find($user->id);
        
        return response()->json([
            'success' => 'User created.',
            'user' => $user
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $user = User::with('roles')->find($id);

        if(!$user) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                'message' => '404 not found',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'status' => ['required', 'in:active,delete'],
            'new_password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,author,translation_editor'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;
        if($request->new_password) {
            $user->new_password = bcrypt($request->new_password);
        }
        $user->save();
        $user->syncRoles($request->role);
        $user = User::with('roles')->find($id);
        
        return response()->json([
            'success' => 'User updated.',
            'user' => $user,
        ]);
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => 'User deleted.',
        ]);
    }
}
