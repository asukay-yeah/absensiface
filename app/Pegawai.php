<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = [
        'nama', 'nip', 'jabatan', 'tim', 'face_descriptor'
    ];

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'pegawai_id', 'id');
    }
}
