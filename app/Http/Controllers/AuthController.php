<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function home(){
        if(Auth::check()){
            return Inertia::render("Dashboard");
        }
        return Inertia::render('Home');
    }

    public function loginForm()
    {
        return Inertia::render('Auth/Login');
    }

    public function registerForm()
    {
        return Inertia::render('Auth/Register', [
            'departments' => Department::select('id', 'name')->get(),
            'roles' => Role::where('code', '!=', 'admin')
                ->select('id', 'label')
                ->get(),
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'department_id' => 'required|exists:departments,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect('/login')->with('success', value: 'ลงทะเบียนสำเร็จ');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'ชื่อผู้เข้าใช้งานหรือรหัสผ่านผิด โปรดกรอกใหม่อีกครั้ง');
        }

        $request->session()->regenerate();
        return redirect('/dashboard')->with('success', 'ลงชื่อเข้าใช้งานสำเร็จ');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
