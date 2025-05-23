<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use App;
use App\Models\User;
use App\Models\Post;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;

class AuthController extends Controller
{
    protected $is_default_locale = true;
    
    
    public function loginPage()
    {
        $categories = Category::all();
        
        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.auth.login';
        } else {
            $view = 'frontend.auth.login';
        }
        return view($view, [
            'categories' => $categories,
        ]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);
        
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home')->with('status', 'Вы вошли в аккаунт');
        }
        return redirect()->route('login-frontend')->withErrors('Login details are not valid');
    }
    public function registerPage()
    {
        $categories = Category::all();
        
        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.auth.register';
        } else {
            $view = 'frontend.auth.register';
        }
        return view($view, [
            'categories' => $categories,
        ]);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'personal_data' => ['required'],
        ]);
        
        
        $user = new User();
        $user->name = $request->name;
        //$user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        
        Auth::login($user);

        return redirect()->route('home')->with('status', 'Вы зарегистрировались и вошли в аккаунт');
    }
    public function logout()
    {
        Session::flush();
        
        Auth::logout();

        return redirect()->route('home')->with('status', 'Вы вышли из аккаунта');
    }
}
