<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Draft;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DokumenUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Memastikan pengguna harus login
    }

    public function input()
    {
        return view('input_dokumen');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul_dokumen' => 'required|string|max:255',
            'deskripsi_dokumen' => 'nullable|string',
            'kategori_dokumen' => 'required|string',
            'validasi_dokumen' => 'required|string',
            'tahun_dokumen' => 'required|integer|min:1900|max:2100',
            'dokumen_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'tags' => 'nullable|string',
            'permissions' => 'array', // Validasi bahwa permissions adalah array
        ]);
    
        // Proses dan simpan file yang diunggah
        if ($request->hasFile('dokumen_file')) {
            $file = $request->file('dokumen_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');
        } else {
            return redirect()->back()->withInput()->withErrors(['dokumen_file' => 'File dokumen wajib diunggah.']);
        }
    
        // Mengambil dan menggabungkan permissions menjadi string yang dipisahkan oleh koma
        $permissions = implode(',', $request->input('permissions', []));
    
        // Simpan data dokumen ke dalam database
        $dokumen = Dokumen::create([
            'judul_dokumen' => $validatedData['judul_dokumen'],
            'deskripsi_dokumen' => $validatedData['deskripsi_dokumen'],
            'kategori_dokumen' => $validatedData['kategori_dokumen'],
            'validasi_dokumen' => $validatedData['validasi_dokumen'],
            'tahun_dokumen' => $validatedData['tahun_dokumen'],
            'dokumen_file' => '/storage/' . $filePath,
            'tags' => $validatedData['tags'] ?? null,
            'view' => $permissions, // Simpan permissions di kolom view
            'created_by' => auth()->user()->name, // Gunakan nama pengguna yang sedang login
        ]);
    
        // Redirect ke halaman list dokumen dengan pesan sukses
        return redirect()->route('list-dokumen-user')->with('success', 'Dokumen berhasil ditambahkan.');
    }
    

    public function listDokumen()
    {
        $documents = Dokumen::where('status', '!=', 'draft')->get();
        return view('list-dokumen-user', ['documents' => $documents]);
    }

    public function processList(Request $request)
    {
        return redirect()->route('list-dokumen-user')->with('success', 'Data berhasil diproses.');
    }

    public function edit($id)
    {
        $document = Dokumen::findOrFail($id);
        return view('edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = Dokumen::findOrFail($id);
        History::create([
            'dokumen_id' => $document->id,
            'judul_dokumen' => $document->judul_dokumen,
            'deskripsi_dokumen' => $document->deskripsi_dokumen,
            'kategori_dokumen' => $document->kategori_dokumen,
            'validasi_dokumen' => $document->validasi_dokumen,
            'tahun_dokumen' => $document->tahun_dokumen,
            'dokumen_file' => $document->dokumen_file,
            'tags' => $document->tags,
        ]);

        $validatedData = $request->validate([
            'judul_dokumen' => 'required|string|max:255',
            'deskripsi_dokumen' => 'required|string',
            'kategori_dokumen' => 'required|string',
            'validasi_dokumen' => 'required|string',
            'tahun_dokumen' => 'required|int',
            'dokumen_file' => 'nullable|file|mimes:pdf,docx,jpeg,png,jpg|max:2048',
            'tags' => 'nullable|string',
            'permissions' => 'nullable|array',
            'created_by' => 'nullable|string',
        ]);

        if ($request->hasFile('dokumen_file')) {
            if ($document->dokumen_file) {
                Storage::disk('public')->delete('documents/' . $document->dokumen_file);
            }
            $fileName = $request->dokumen_file->getClientOriginalName();
            $request->dokumen_file->storeAs('public/documents', $fileName);
            $document->dokumen_file = $fileName;
        }

        // Menggabungkan nilai checkbox menjadi string terpisah koma
        $viewPermissions = implode(',', $request->permissions ?? []);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $document->judul_dokumen = $validatedData['judul_dokumen'];
        $document->deskripsi_dokumen = $validatedData['deskripsi_dokumen'];
        $document->kategori_dokumen = $validatedData['kategori_dokumen'];
        $document->validasi_dokumen = $validatedData['validasi_dokumen'];
        $document->tahun_dokumen = $validatedData['tahun_dokumen'];
        $document->tags = $validatedData['tags'] ?? null;
        $document->view = $viewPermissions; // Menyimpan nilai checkbox ke kolom view
        $document->created_by = $validatedData['created_by'] ?? $user->name;
        $document->save();

        return redirect()->route('list-dokumen-user')->with('success', 'Details dokumen berhasil diperbarui.');
    }

    public function moveToDraft($id)
    {
        $document = Dokumen::findOrFail($id);

        Log::info('Menghapus dokumen dengan ID: ' . $id, ['document' => $document]);

        $user = Auth::user();
        Log::info('User info:', ['user' => $user]);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $draft = Draft::create([
            'judul_dokumen' => $document->judul_dokumen,
            'deskripsi_dokumen' => $document->deskripsi_dokumen,
            'kategori_dokumen' => $document->kategori_dokumen,
            'validasi_dokumen' => $document->validasi_dokumen,
            'tahun_dokumen' => $document->tahun_dokumen,
            'dokumen_file' => $document->dokumen_file,
            'tags' => $document->tags,
            'status' => 'draft',
            'created_by' => $document->created_by,
        ]);

        Log::info('Dokumen dipindahkan ke draft', ['draft' => $draft]);

        $document->delete();

        Log::info('Dokumen dihapus dari tabel dokumens', ['document' => $document]);

        return redirect()->route('list-dokumen-user')->with('success', 'Dokumen berhasil dihapus');
    }

    public function getUserName()
    {
        $user = Auth::user();
        return response()->json(['name' => $user->name]);
    }

    public function history($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $histories = $dokumen->histories()->orderBy('created_at', 'desc')->get();

        return view('history', compact('dokumen', 'histories'));
    }
}
