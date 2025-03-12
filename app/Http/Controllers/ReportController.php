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
                // Cek apakah input dalam format "7 maret 2025" atau "7 maret"
                if (preg_match('/(\d{1,2})\s+([a-zA-Z]+)(?:\s+(\d{4}))?/', strtolower($search), $matches)) {
                    $tanggal = $matches[1];
                    $bulanText = $matches[2];
                    $tahun = isset($matches[3]) ? $matches[3] : date('Y');
            
                    if (isset($bulanMap[$bulanText])) {
                        $query->whereDate('tanggal', Carbon::create($tahun, $bulanMap[$bulanText], $tanggal)->toDateString());
                    }
                } 
                // Tambahkan kondisi untuk menangani pencarian "maret 2025"
                elseif (preg_match('/([a-zA-Z]+)\s+(\d{4})/', strtolower($search), $matches)) {
                    $bulanText = $matches[1]; // Nama bulan
                    $tahun = $matches[2]; // Tahun
            
                    if (isset($bulanMap[$bulanText])) {
                        $bulan = $bulanMap[$bulanText];
                        $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                    }
                }
                // Jika input hanya berupa bulan (contoh: "maret"), cari berdasarkan bulan tahun ini
                elseif (isset($bulanMap[strtolower($search)])) {
                    $bulan = $bulanMap[strtolower($search)];
                    $tahun = date('Y'); // Gunakan tahun sekarang jika tidak disebutkan
            
                    $query->whereMonth('tanggal', $bulan);
                } 
                // Jika input hanya berupa tahun (contoh: "2025"), cari berdasarkan tahun
                elseif (preg_match('/^(\d{4})$/', $search, $matches)) {
                    $tahun = $matches[1];
                    $query->whereYear('tanggal', $tahun);
                }
                // Jika input bukan tanggal atau bulan, cari berdasarkan pegawai atau NIP
                else {
                    $query->whereHas('pegawai', function ($query) use ($search) {
                        $query->where('nama', 'like', '%' . $search . '%')
                            ->orWhere('nip', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('keterangan', 'like', '%' . $search . '%');
                    })->orWhere('tanggal', 'like', '%' . $search . '%');
                }
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk')
            ->get();
            
        return view('admin.report', compact('kehadiran'));
    }
}