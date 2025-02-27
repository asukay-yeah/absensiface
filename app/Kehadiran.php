<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadirans';

    protected $fillable = [
        'pegawai_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'nomor_duduk', 'status', 'keterangan'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id');
    }
}