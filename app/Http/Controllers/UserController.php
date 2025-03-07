<?php

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

        // Define default time windows
        $checkInStart = '04:30';
        $checkInEnd = '12:30';
        $checkOutStart = '15:00';
        $checkOutEnd = '18:00';
        $onTimeBeforeHour = '08:30';

        // Check if the employee is security and handle their shifts
        if ($pegawai->jabatan === 'security') {
            if ($pegawai->security_shift === 'shift_1') {
                // Security shift 1: 05:00 - 19:00
                $checkInStart = '06:30';
                $checkInEnd = '07:30';
                $checkOutStart = '19:00';
                $checkOutEnd = '19:30';
                $onTimeBeforeHour = '07:20';
            } elseif ($pegawai->security_shift === 'shift_2') {
                // Security shift 2: 19:00 - 05:00
                $checkInStart = '18:30';
                $checkInEnd = '19:30';
                $checkOutStart = '06:30';
                $checkOutEnd = '07:30';
                $onTimeBeforeHour = '19:20';
            }
        }

        // 1. ABSEN DATANG
        if ($absenType === 'datang') {
            if ($now->between(Carbon::parse($checkInStart), Carbon::parse($checkInEnd))) {
                if (Kehadiran::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->exists()) {
                    $nomor_meja = Kehadiran::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->first()->nomor_duduk;
                    return back()->with([
                        'warning' => 'Anda sudah absen datang hari ini.',
                        'meja' => '‎' . $nomor_meja
                    ]);
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
                $keterangan = $now->lte(Carbon::parse($onTimeBeforeHour)) ? 'tepat waktu' : 'terlambat';

                // Simpan kehadiran
                Kehadiran::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $today,
                    'jam_masuk' => $now->format('H:i:s'),
                    'nomor_duduk' => $nomor_duduk,
                    'status' => $status,
                    'keterangan' => $keterangan
                ]);

                return back()->with([
                    'success' => "Absen datang berhasil! Status: {$keterangan}.",
                    'meja' => "{$nomor_duduk}"
                ]);
            } else {
                // Create appropriate error message based on time window
                $timeWindow = $checkInStart . ' - ' . $checkInEnd;
                return back()->with('error', "Waktu absen datang adalah pukul {$timeWindow}!");
            }
        }

        // 2. ABSEN PULANG
        else if ($absenType === 'pulang') {
            // Special handling for security shift 2 which spans midnight
            $isWithinCheckoutWindow = false;
            $attendanceDate = $today; // Default to today
            
            if ($pegawai->jabatan === 'security' && $pegawai->security_shift === 'shift_2') {
                // For shift 2, valid checkout times are 04:30-06:00
                $isWithinCheckoutWindow = $now->between(Carbon::parse($checkOutStart), Carbon::parse($checkOutEnd));
                
                // If checking out in the morning (e.g., 05:00), look for attendance from yesterday
                if ($isWithinCheckoutWindow && $now->hour < 12) {
                    $attendanceDate = Carbon::yesterday()->toDateString();
                }
            } else {
                // For regular staff and shift 1
                $isWithinCheckoutWindow = $now->between(Carbon::parse($checkOutStart), Carbon::parse($checkOutEnd));
            }
            
            if ($isWithinCheckoutWindow) {
                // Look for attendance record based on the determined date
                $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)
                                    ->where('tanggal', $attendanceDate)
                                    ->first();

                if (!$kehadiran) {
                    return back()->with('error', 'Anda belum absen datang hari ini!');
                }

                if ($kehadiran->jam_pulang !== null) {
                    return back()->with('error', 'Anda sudah absen pulang hari ini!');
                }

                $kehadiran->update(['jam_pulang' => $now->format('H:i:s')]);

                return back()->with('success', 'Absen pulang berhasil! Selamat beristirahat.');
            } else {
                // Create appropriate error message based on time window
                $timeWindow = $checkOutStart . ' - ' . $checkOutEnd;
                return back()->with('error', "Waktu absen pulang adalah pukul {$timeWindow}!");
            }
        }
    }

    public function checkAbsentStatus()
    {
        $now = Carbon::now();

        // Jika waktu sudah melewati jam 09:30
        if ($now->format('H:i') >= '12:31') {
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