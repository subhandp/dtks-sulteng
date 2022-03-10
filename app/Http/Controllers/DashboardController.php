<?php

namespace App\Http\Controllers;
// use App\SuratKeluar;
// use App\SuratMasuk;
// use App\Klasifikasi;
// use App\User;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
   
    public function index()
    {
        // $suratkeluar = SuratKeluar::where('users_id', Auth::id())->count();
        // $suratmasuk = SuratMasuk::where('users_id', Auth::id())->count();
        // $klasifikasi = Klasifikasi::where('users_id', Auth::id())->count();
        // $pengguna = User::count();
        // return view('dashboard', compact('suratkeluar','suratmasuk','klasifikasi','pengguna'));
        
        return view('dashboard');



    }
}
