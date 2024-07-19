<?php

namespace App\Http\Controllers;

use App\Models\Validasi; // Menggunakan model Validasi
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    public function getValidasiDokumen()
    {
        $validasiList = Validasi::all()->pluck('nama_validasi')->toArray();
    
        return response()->json($validasiList);
    }

    

    public function index()
    {
        $validasiList = Validasi::all();
        return view('validasi.index', compact('validasiList'));
    }

    public function create()
    {
        return view('validasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_validasi' => 'required|string|max:255',
        ]);

        Validasi::create($request->all());

        return redirect()->route('validasi.index')->with('success', 'Data validasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $validasi = Validasi::findOrFail($id);
        return view('validasi.edit', compact('validasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_validasi' => 'required|string|max:255',
        ]);

        $validasi = Validasi::findOrFail($id);
        $validasi->update($request->all());

        return redirect()->route('validasi.index')->with('success', 'Data validasi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $validasi = Validasi::findOrFail($id);
        $validasi->delete();

        return redirect()->route('validasi.index')->with('success', 'Data validasi berhasil dihapus.');
    }
}
