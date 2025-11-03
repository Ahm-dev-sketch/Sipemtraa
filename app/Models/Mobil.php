<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_polisi',
        'jenis',
        'kapasitas',
        'tahun',
        'merk',
        'status',
    ];

    // Relationship: Mobil memiliki satu Supir
    public function supir()
    {
        return $this->hasOne(Supir::class);
    }

    // Relationship: Mobil memiliki banyak Jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
