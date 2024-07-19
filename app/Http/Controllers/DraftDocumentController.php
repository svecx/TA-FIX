<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Draft;
use Illuminate\Support\Facades\Auth;

class DraftDocumentController extends Controller
{
    // Menampilkan halaman draft dokumen
    public function index() {
        // Assuming you have a Draft model to fetch data from the 'draft' table
        $userName = Auth::user()->name;
        $draftDokumens = Draft::where('created_by', $userName)->get();
    
        return view('draft-dokumens.index', compact('draftDokumens'));
    }
    

    public function delete($id)
    {
        $draft = Draft::findOrFail($id);
        $draft->delete();

        return redirect()->route('draft-dokumen')->with('status', 'Dokumen berhasil dihapus dari draft');
    }

    public function unarchive($id)
    {
        $draft = Draft::findOrFail($id);

        // Pindahkan data dari Draft ke Dokumen
        Dokumen::create([
            'judul_dokumen' => $draft->judul_dokumen,
            'created_by' => $draft->created_by,
            'deskripsi_dokumen' => $draft->deskripsi_dokumen,
            'kategori_dokumen' => $draft->kategori_dokumen,
            'validasi_dokumen' => $draft->validasi_dokumen,
            'tahun_dokumen' => $draft->tahun_dokumen,
            'dokumen_file' => $draft->dokumen_file,
            'tags' => $draft->tags,
        ]);

        // Hapus data dari tabel draft
        $draft->delete();

        // Redirect ke halaman list dokumen dengan pesan sukses
        return redirect()->route('list-dokumen')->with('status', 'Dokumen berhasil dipindahkan ke list dokumen');
    }

    public function moveToDraft($id)
    {
        $dokumen = Dokumen::find($id);
        if ($dokumen) {
            Draft::create([
                'judul_dokumen' => $dokumen->judul_dokumen,
                'created_by' => $draft->created_by,
                'deskripsi_dokumen' => $dokumen->deskripsi_dokumen,
                'kategori_dokumen' => $dokumen->kategori_dokumen,
                'validasi_dokumen' => $dokumen->validasi_dokumen,
                'tahun_dokumen' => $dokumen->tahun_dokumen,
                'dokumen_file' => $dokumen->dokumen_file,
                'tags' => $dokumen->tags,
                'status' => 'draft',
            ]);

            return redirect()->route('dokumens.index')->with('status', 'Dokumen dipindahkan ke draft');
        } else {
            return redirect()->route('dokumens.index')->with('error', 'Dokumen tidak ditemukan');
        }
    }
}