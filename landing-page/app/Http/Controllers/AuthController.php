<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Session::put('admin_id', $admin->id);
            Session::put('admin_nama', $admin->nama);
            Session::put('admin_login', true);

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . $admin->nama);
        }

        return back()->with('error', 'Username atau password salah!');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/')->with('success', 'Berhasil logout');
    }
}