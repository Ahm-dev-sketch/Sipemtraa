<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Supir untuk mengelola data pengemudi kendaraan travel
 * Menyimpan informasi supir dan relasi dengan mobil yang dikemudikan
 */
class Supir extends Model
{
    use HasFactory;

    // Atribut yang dapat diisi massal untuk supir
    protected $fillable = [
        'nama',
        'telepon',
        'mobil_id',
    ];

    // Relasi dengan model Mobil (satu supir mengemudikan satu mobil)
    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }
}
