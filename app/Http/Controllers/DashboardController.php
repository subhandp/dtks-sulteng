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
       
       
        // $lava = new Lavacharts; // See note below for Laravel

        // $votes  = \Lava::DataTable();

        // $votes->addStringColumn('Food Poll')
        //     ->addNumberColumn('Votes')
        //     ->addRow(['Tacos',  rand(1000,5000)])
        //     ->addRow(['Salad',  rand(1000,5000)])
        //     ->addRow(['Pizza',  rand(1000,5000)])
        //     ->addRow(['Apples', rand(1000,5000)])
        //     ->addRow(['Fish',   rand(1000,5000)]);

        // \Lava::BarChart('Votes', $votes);

        // $population = \Lava::DataTable();

        // $population->addDateColumn('Year')
        //         ->addNumberColumn('Number of People')
        //         ->addRow(['2006', 623452])
        //         ->addRow(['2007', 685034])
        //         ->addRow(['2008', 716845])
        //         ->addRow(['2009', 757254])
        //         ->addRow(['2010', 778034])
        //         ->addRow(['2011', 792353])
        //         ->addRow(['2012', 839657])
        //         ->addRow(['2013', 842367])
        //         ->addRow(['2014', 873490]);

        // \Lava::AreaChart('Population', $population, [
        //     'title' => 'Population Growth',
        //     'legend' => [
        //         'position' => 'in'
        //     ]
        //     ]);

        $class_menu_data_dashboard = "menu-open";
        return view('dashboard', compact('class_menu_data_dashboard'));



    }
}
