<?php

namespace App\Http\Controllers;

use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriDokumenController extends Controller
{
    public function getKategoriDokumen()
    {
        $user = Auth::user();
        $kategoriDokumen = [];

        if ($user->jabatan === 'Dosen') {
            $kategoriDokumen = KategoriDokumen::whereIn('nama_dokumen', [
                'Dokumen Pendidikan',
                'Dokumen Penelitian',
                'Dokumen Sumber Daya Manusia'
            ])->get();
        } elseif ($user->jabatan === 'Mahasiswa' || $user->jabatan === 'Adm') {
            $kategoriDokumen = KategoriDokumen::where('nama_dokumen', 'Dokumen Pendidikan')->get();
        } else {
            $kategoriDokumen = KategoriDokumen::all();
        }

        return response()->json($kategoriDokumen);
    }

    public function index()
    {
        $kategoriDokumen = KategoriDokumen::all();
        return view('kategori_dokumen.index', compact('kategoriDokumen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
        ]);

        KategoriDokumen::create($request->all());

        return redirect()->route('kategori-dokumen.index')->with('success', 'Kategori Dokumen berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategoriDokumen = KategoriDokumen::findOrFail($id);
        return view('kategori_dokumen.edit', compact('kategoriDokumen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
        ]);

        $kategoriDokumen = KategoriDokumen::findOrFail($id);
        $kategoriDokumen->update($request->all());

        return redirect()->route('kategori-dokumen.index')->with('success', 'Kategori Dokumen berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kategoriDokumen = KategoriDokumen::findOrFail($id);
        $kategoriDokumen->delete();

        return redirect()->route('kategori-dokumen.index')->with('success', 'Kategori Dokumen berhasil dihapus.');
    }
}
