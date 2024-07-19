<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $fillable = [
        'dokumen_id', 'judul_dokumen', 'deskripsi_dokumen', 'kategori_dokumen', 'validasi_dokumen','status_file', 'tahun_dokumen','dokumen_link', 'dokumen_file', 'tags', 'created_by','view'
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

}