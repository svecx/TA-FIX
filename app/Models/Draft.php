<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    protected $table = 'draft';

    protected $fillable = [
            'judul_dokumen',
            'created_by',
            'deskripsi_dokumen',
            'kategori_dokumen',
            'validasi_dokumen',
            'tahun_dokumen',
            'dokumen_file',
            'tags',
            'status',
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'document_id');
    }
}
