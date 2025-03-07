<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kehadiran;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = request('search');

        // Mapping nama bulan ke angka
        $bulanMap = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
        ];

        $kehadiran = Kehadiran::query()
            ->with('pegawai')
            ->when($search, function ($query) use ($search, $bulanMap) {
                // Cek apakah input memiliki angka (kemungkinan tanggal)
                if (preg_match('/(\d{1,2})\s+([a-zA-Z]+)(?:\s+(\d{4}))?/', strtolower($search), $matches)) {
                    $tanggal = $matches[1]; // Ambil tanggal (misal: 7)
                    $bulanText = $matches[2]; // Ambil nama bulan (misal: april)
                    $tahun = isset($matches[3]) ? $matches[3] : date('Y'); // Ambil tahun atau gunakan tahun sekarang
                    
                    // Jika nama bulan valid, cari berdasarkan tanggal lengkap
                    if (isset($bulanMap[$bulanText])) {
                        $query->whereDate('tanggal', Carbon::create($tahun, $bulanMap[$bulanText], $tanggal)->toDateString());
                    }
                } else {
                    // Jika bukan format tanggal, lakukan pencarian biasa (pegawai dan nip)
                    $query->whereHas('pegawai', function ($query) use ($search) {
                        $query->where('nama', 'like', '%' . $search . '%')
                            ->orWhere('nip', 'like', '%' . $search . '%');
                    })->orWhere('tanggal', 'like', '%' . $search . '%');
                }
            })
            ->orderBy('tanggal', 'jam_masuk')
            ->get();

        return view('admin.report', compact('kehadiran'));
    }

}
