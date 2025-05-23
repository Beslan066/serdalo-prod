<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]); 

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);//401
        }
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        $token = $user->createToken($request->email)->plainTextToken; 
        
        return response()->json(['token' => $token], 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);    
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);//401
        }
     
        $user = User::where('email', $request->email)->first();
     
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 200);//401
        }
     
        return response()->json(['token' => $user->createToken($request->email)->plainTextToken]);
    }
    public function user(Request $request)
    {
        $user = User::with(['roles', 'permissions'])->find(Auth::id());
        
        return response()->json([
            'user' => $user,
            'roles' => $user->roles->keyBy('name'),
            'permissions' => $user->permissions->keyBy('name'),
        ], 200);
    }
}
