<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Kehadiran;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $this->checkAbsentStatus();
        return view('guest.index');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|string',
            'absen_type' => 'required|in:datang,pulang'
        ]);

        $this->checkAbsentStatus();

        // Check if employee exists based on nama and NIP
        $pegawai = Pegawai::where('nama', $request->nama)->where('nip', $request->nip)->first();

        if (!$pegawai) {
            return back()->with('error', 'Nama atau NIP tidak sesuai!');
        }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now();
        $absenType = $request->absen_type;

        // 1. ABSEN DATANG (04:30 - 09:30)
        if ($absenType === 'datang') {
            if ($now->between(Carbon::parse('04:30'), Carbon::parse('09:30'))) {
                if (Kehadiran::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->exists()) {
                    return back()->with('warning', 'Anda sudah absen datang hari ini. Nomor duduk anda : ' . Kehadiran::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->first()->nomor_duduk);
                }

                // Ambil nomor kursi acak hanya untuk staff dan magang
                $nomor_duduk = 0;
                if (in_array($pegawai->jabatan, ['staff', 'magang'])) {
                    $nomor_terpakai = Kehadiran::where('tanggal', $today)->pluck('nomor_duduk')->toArray();
                    $nomor_tersedia = array_diff(range(1, 60), $nomor_terpakai);

                    if (empty($nomor_tersedia)) {
                        return back()->with('error', 'Semua kursi sudah terisi hari ini!');
                    }

                    $nomor_duduk = $nomor_tersedia[array_rand($nomor_tersedia)];
                }

                // Tentukan status & keterangan
                $status = 'hadir';
                $keterangan = $now->lte(Carbon::parse('08:30')) ? 'tepat waktu' : 'terlambat';

                // Simpan kehadiran
                Kehadiran::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $today,
                    'jam_masuk' => $now->format('H:i:s'),
                    'nomor_duduk' => $nomor_duduk,
                    'status' => $status,
                    'keterangan' => $keterangan
                ]);

                return back()->with('success', "Absen datang berhasil! Status: {$keterangan}. Nomor duduk Anda: {$nomor_duduk}");
            } else {
                return back()->with('error', 'Waktu absen datang adalah pukul 04:30 - 09:30!');
            }
        }

        // 2. ABSEN PULANG (15:00 - 18:00)
        else if ($absenType === 'pulang') {
            if ($now->between(Carbon::parse('15:00'), Carbon::parse('18:00'))) {
                $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->first();

                if (!$kehadiran) {
                    return back()->with('error', 'Anda belum absen datang hari ini!');
                }

                if ($kehadiran->jam_pulang !== null) {
                    return back()->with('error', 'Anda sudah absen pulang hari ini!');
                }

                $kehadiran->update(['jam_pulang' => $now->format('H:i:s')]);

                return back()->with('success', 'Absen pulang berhasil! Selamat beristirahat.');
            } else {
                return back()->with('error', 'Waktu absen pulang adalah pukul 15:00 - 18:00!');
            }
        }
    }

    public function checkAbsentStatus()
    {
        $now = Carbon::now();

        // Jika waktu sudah melewati jam 11:59
        if ($now->format('H:i') >= '09:31') {
            // Dapatkan semua pegawai yang belum absen hari ini
            $today = $now->toDateString();
            $pegawaiBelumAbsen = Pegawai::whereNotIn('id', function ($query) use ($today) {
                $query->select('pegawai_id')->from('kehadirans')->where('tanggal', $today);
            })->get();

            // Masukkan mereka ke dalam database dengan status tidak hadir dan keterangan alpha
            foreach ($pegawaiBelumAbsen as $pegawai) {
                Kehadiran::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $today,
                    'status' => 'tidak hadir',
                    'keterangan' => 'alpha',
                    'jam_masuk' => null,
                    'jam_pulang' => null
                ]);
            }
        }
    }
}