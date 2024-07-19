<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validasi extends Model
{
    protected $table = 'validasi'; // Sesuaikan dengan nama tabel yang Anda gunakan

    protected $fillable = [
        'nama_validasi',
    ];

    public $timestamps = true; // Atur timestamps ke true jika Anda ingin menggunakan created_at dan updated_at

    // Tambahkan kode tambahan sesuai kebutuhan
}
