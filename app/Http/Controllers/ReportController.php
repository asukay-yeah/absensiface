<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kehadiran;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = request('search');
        // $kehadiran = Kehadiran::with('pegawai')->orderBy('tanggal', 'jam_masuk')->get();
        $kehadiran = Kehadiran::query()
            ->with('pegawai')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('pegawai', function ($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nip', 'like', '%' . $search . '%')
                        ->orWhere('tanggal', 'like', '%' . $search . '%');

                });
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_masuk', 'asc')
            ->get();

        return view('admin.report', compact('kehadiran'));
    }
}
