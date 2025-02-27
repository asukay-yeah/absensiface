<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pegawai;

class DataController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $karyawan = Pegawai::query()
        ->when($search, function($query, $search) {
            $query->where(function($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('jabatan', 'like', '%' . $search . '%')
                ->orWhere('tim', 'like', '%' . $search . '%');
            });
        })
        ->get();
        return view('admin.data', compact('karyawan'));
    }

    // public function create(){
    //     return view('admin.data');
    // }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
            'tim' => 'nullable',
        ]);

        Pegawai::create($request->all());

        return redirect('/data');
    }

    public function show($id)
    {
        $karyawan = Pegawai::findOrFail($id);
        return response()->json($karyawan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
            'tim' => 'nullable',
        ]);

        $karyawan = Pegawai::findOrFail($id);
        $karyawan->update($request->all());

        return response()->json(['success' => 'Data berhasil diperbarui']);
    }

    public function destroy($id){
        $karyawan = Pegawai::findOrFail($id);
        $karyawan->delete();
        return redirect('/data');
    }


}