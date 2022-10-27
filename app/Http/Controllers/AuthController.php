<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth');
    }

    public function loginPmks()
    {
        return view('authPmks');
    }

    public function loginPsks()
    {
        return view('authPsks');
    }

    public function postloginPmks(Request $request)
    {
        // $cre = $request->only('username','password');
        $cre = ['username' => $request->input('username'), 'password' => $request->input('password'), 'role' => 'operator_pmks'];
        if (Auth::attempt($cre)) {
            return redirect('/pmks/dashboard');
            // return redirect()->back()->with('sukses','Email atau Password BENAR!');
        }
        return redirect()->back()->with('sukses','Username atau Password Salah!');
    }

    public function postloginPsks(Request $request)
    {
        // $cre = $request->only('username','password');
        $cre = ['username' => $request->input('username'), 'password' => $request->input('password'), 'role' => 'operator_psks'];

        if (Auth::attempt($cre)) {
            return redirect('/psks/dashboard');
            // return redirect()->back()->with('sukses','Email atau Password BENAR!');
        }
        return redirect()->back()->with('sukses','Username atau Password Salah!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
