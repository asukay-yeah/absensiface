<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kehadiran;


class ReportController extends Controller
{
    public function index()
    {
        $kehadiran = Kehadiran::with('pegawai')->orderBy('tanggal', 'jam_masuk')->get();
        return view('admin.report', compact('kehadiran'));
    }
}
