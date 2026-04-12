<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Repair;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalLaporan = Report::count();
        $totalPerbaikan = Repair::count();
        $laporanPending = Report::where('status', 'pending')->count();
        $laporanDiproses = Report::where('status', 'diproses')->count();
        $laporanSelesai = Report::where('status', 'selesai')->count();
        $adminNama = Session::get('admin_nama');
        
        return view('admin.dashboard', compact(
            'totalLaporan', 'totalPerbaikan', 
            'laporanPending', 'laporanDiproses', 'laporanSelesai',
            'adminNama'
        ));
    }
}