<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function fitur()
    {
        return view('fitur');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function lapor()
    {
        return view('lapor');
    }

    public function laporanSaya()
    {
        return view('laporan-saya');
    }

    public function peta()
    {
        return view('peta');
    }

    public function profil()
    {
        return view('profil');
    }
}