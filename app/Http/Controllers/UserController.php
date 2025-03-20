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

        // Define time windows based on employee job
        if ($pegawai->jabatan === 'security') {
            // Fixed time windows for all security staff
            // Shift 1: 07:00-19:00
            $checkInStartShift1 = '06:30';
            $checkInEndShift1 = '07:20';
            $checkOutStartShift1 = '18:30';
            $checkOutEndShift1 = '19:20';
            
            // Shift 2: 19:00-07:00
            $checkInStartShift2 = '18:30';
            $checkInEndShift2 = '19:20';
            $checkOutStartShift2 = '06:30';
            $checkOutEndShift2 = '07:20';
            
            $onTimeBeforeHour = '07:00'; // For shift 1
            $onTimeBeforeHourShift2 = '19:00'; // For shift 2
        } else {
            // Regular staff
            $checkInStart = '04:30';
            $checkInEnd = '09:30';
            $checkOutStart = '15:00';
            $checkOutEnd = '18:00';
            $onTimeBeforeHour = '08:30';
        }

        // 1. ABSEN DATANG
        if ($absenType === 'datang') {
            if ($pegawai->jabatan === 'security') {
                // Security can check in during either shift 1 or shift 2 time windows
                $isWithinShift1CheckIn = $now->between(Carbon::parse($checkInStartShift1), Carbon::parse($checkInEndShift1));
                $isWithinShift2CheckIn = $now->between(Carbon::parse($checkInStartShift2), Carbon::parse($checkInEndShift2));
                
                if (!$isWithinShift1CheckIn && !$isWithinShift2CheckIn) {
                    return back()->with('error', 'Waktu absen datang untuk security adalah pukul 06:30-07:20 atau 18:30-19:20!');
                }
                
                // Check if already checked in today
                if (Kehadiran::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->exists()) {
                    $nomor_meja = Kehadiran::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->first()->nomor_duduk;
                    return back()->with([
                        'warning' => 'Anda sudah absen datang hari ini.',
                        'meja' => '‎' . $nomor_meja
                    ]);
                }
                
                // Determine which shift the security is checking in for
                $currentShift = $isWithinShift1CheckIn ? 'shift_1' : 'shift_2';
                
                // Set on-time criteria based on shift
                $isOnTime = $currentShift === 'shift_1' 
                    ? $now->lte(Carbon::parse($onTimeBeforeHour)) 
                    : $now->lte(Carbon::parse($onTimeBeforeHourShift2));
                
                // Tentukan status & keterangan
                $status = 'hadir';
                $keterangan = $isOnTime ? 'tepat waktu' : 'terlambat';
                
                // Create attendance record with the shift information in the notes field
                Kehadiran::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $today,
                    'jam_masuk' => $now->format('H:i:s'),
                    'nomor_duduk' => 0, // Security doesn't get assigned a random desk
                    'status' => $status,
                    'keterangan' => $keterangan . ' (' . $currentShift . ')'
                ]);
                
                return back()->with([
                    'success' => "Absen datang berhasil! Status: {$keterangan}.",
                    'meja' => "0"
                ]);
            } else {
                // Regular staff check-in
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
                    return back()->with('error', 'Waktu absen datang adalah pukul 04:30 - 09:30!');
                }
            }
        }

        // 2. ABSEN PULANG
        else if ($absenType === 'pulang') {
            if ($pegawai->jabatan === 'security') {
                // Security can check out during either shift 1 or shift 2 time windows
                $isWithinShift1CheckOut = $now->between(Carbon::parse($checkOutStartShift1), Carbon::parse($checkOutEndShift1));
                $isWithinShift2CheckOut = $now->between(Carbon::parse($checkOutStartShift2), Carbon::parse($checkOutEndShift2));
                
                if (!$isWithinShift1CheckOut && !$isWithinShift2CheckOut) {
                    return back()->with('error', 'Waktu absen pulang untuk security adalah pukul 18:30-19:20 atau 06:30-07:20!');
                }
                
                // Determine which date to check based on the current time
                $attendanceDate = $today;
                
                // If checking out in the morning hours (shift 2 checkout), check yesterday's record
                if ($isWithinShift2CheckOut && $now->hour < 12) {
                    $attendanceDate = Carbon::yesterday()->toDateString();
                }
                
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
                // Regular staff check-out
                if ($now->between(Carbon::parse($checkOutStart), Carbon::parse($checkOutEnd))) {
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