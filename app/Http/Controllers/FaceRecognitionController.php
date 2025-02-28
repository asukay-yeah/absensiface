<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pegawai;

class FaceRecognitionController extends Controller
{
    public function getAllFaces()
    {
        try {
            $pegawai = Pegawai::select('id', 'nama', 'nip', 'face_descriptor')->get();
            
            $faces = $pegawai->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'nama' => $employee->nama,
                    'nip' => $employee->nip,
                    'descriptor' => $employee->face_descriptor
                ];
            });
            
            return response()->json([
                'success' => true,
                'faces' => $faces
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve employee face data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
