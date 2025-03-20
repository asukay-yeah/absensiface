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
        ->orderBy('id', 'desc')
        ->paginate(10);
        return view('admin.data', compact('karyawan'));
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
            'tim' => 'nullable',
            'face_descriptor' => 'nullable',
            'security_shift' => 'nullable'
        ]);

        $data = $request->all();
        
        // Store the face descriptor if provided
        if ($request->has('face_descriptor') && !empty($request->face_descriptor)) {
            $data['face_descriptor'] = $request->face_descriptor;
        }

        if ($request->jabatan === 'security' && $request->has('security_shift')) {
            $data['security_shift'] = $request->security_shift;
        } else {
            $data['security_shift'] = null;
        }

        Pegawai::create($data);

        return redirect('/data')->with('success', 'Data pegawai berhasil ditambahkan');
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
            'face_descriptor' => 'nullable',
            'security_shift' => 'nullable'
        ]);

        $karyawan = Pegawai::findOrFail($id);
        
        $data = $request->all();
        
        // Update the face descriptor if provided
        if ($request->has('face_descriptor') && !empty($request->face_descriptor)) {
            $data['face_descriptor'] = $request->face_descriptor;
        }

        if ($request->jabatan === 'security' && $request->has('security_shift')) {
            $data['security_shift'] = $request->security_shift;
        } else {
            $data['security_shift'] = null;
        }
        
        $karyawan->update($data);

        return response()->json(['success' => 'Data berhasil diperbarui']);
    }

    public function destroy($id){
        $karyawan = Pegawai::findOrFail($id);
        $karyawan->delete();
        return redirect('/data');
    }


    /** 
     * Get the security shift infrmation for an employee
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse 
     */
    public function getSecurityShift($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return response()->json(['shift' => $pgawai->security_shift]);
    }
}