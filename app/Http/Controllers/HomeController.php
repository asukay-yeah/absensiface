<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pegawai;
use App\Kehadiran;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Array nama bulan dalam bahasa Indonesia
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $hadirPerBulan = [];
        $tidakHadirPerBulan = [];

        foreach (range(1, 12) as $month) {
            $year = Carbon::now()->year;

            // Jumlah hadir
            $hadirCount = Kehadiran::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('status', 'hadir')
                ->count();

            // Jumlah tidak hadir
            $tidakHadirCount = Kehadiran::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('status', 'tidak hadir')
                ->count();

            $hadirPerBulan[] = $hadirCount;
            $tidakHadirPerBulan[] = $tidakHadirCount;
        }


        // Data hadir dan tidak hadir per hari
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $hadirPerHari = [];
        $tidakHadirPerHari = [];

        foreach ($days as $index => $day) {
            $date = Carbon::now()->startOfWeek()->addDays($index)->toDateString();
            $hadirPerHari[] = Kehadiran::where('tanggal', $date)->where('status', 'hadir')->count();
            $tidakHadirPerHari[] = Kehadiran::where('tanggal', $date)->where('status', 'tidak hadir')->count();
        }
        

        // Ambil tanggal hari ini
        $today = Carbon::today()->toDateString();

        // Total kehadiran per hari
        $hadir = Kehadiran::where('tanggal', $today)->where('status', 'hadir')->count();
        $alpha = Kehadiran::where('tanggal', $today)->where('status', 'tidak hadir')->count();

        // Jumlah pegawai yang tepat waktu dan terlambat
        $tepat = Kehadiran::where('tanggal', $today)->where('keterangan', 'tepat waktu')->count();
        $terlambat = Kehadiran::where('tanggal', $today)->where('keterangan', 'terlambat')->count();


        $karyawan = Pegawai::all();
        $magang = Pegawai::where('jabatan', 'magang')->count();
        return view('admin.home', compact('karyawan', 'magang', 'hadir', 'alpha', 'tepat', 'terlambat', 'hadirPerHari', 'tidakHadirPerHari', 'hadirPerBulan', 'tidakHadirPerBulan'));
    }
}
