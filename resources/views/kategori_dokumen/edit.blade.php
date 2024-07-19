@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Edit Kategori Dokumen</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('kategori-dokumen.update', $kategoriDokumen->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
            <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" value="{{ $kategoriDokumen->nama_dokumen }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
