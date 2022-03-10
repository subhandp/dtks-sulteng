<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PmksController extends Controller
{
    
    public function index()
    {
        // $data_suratmasuk = SuratMasuk::where('users_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        $data_pmks_import = [];
        $class_menu_pmks = "menu-open";
        $class_menu_pmks_import = "sub-menu-open";
        $class_menu_pmks_daftar = "";

        return view('pmks.index', compact('data_pmks_import','class_menu_pmks','class_menu_pmks_import','class_menu_pmks_daftar'));

        // return view('suratmasuk.index',['data_suratmasuk'=> $data_suratmasuk]);
    }

    public function daftarpmks()
    {
        // $data_suratmasuk = SuratMasuk::where('users_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        $data_daftar_pmks = [];
        $class_menu_pmks = "menu-open";
        $class_menu_daftar_pmks = "sub-menu-open";
        $class_menu_pmks_import = "";

        return view('pmks.daftar', compact('data_daftar_pmks','class_menu_pmks','class_menu_daftar_pmks','class_menu_pmks_import'));

    }

}
