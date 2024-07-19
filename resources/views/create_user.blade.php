@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="row w-100">
        <div class="col-md-8 offset-md-2">
            <h3 class="mb-4 text-center">Register User</h3>
            <form action="{{ route('simpan-user') }}" method="POST">
                @csrf
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="name" class="col-sm-3 col-form-label">Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="email" class="col-sm-3 col-form-label">Email:</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="password" class="col-sm-3 col-form-label">Password:</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="password_confirmation" class="col-sm-3 col-form-label">Confirm Password:</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jabatan" class="col-sm-3 col-form-label">Jabatan:</label>
                    <div class="col-sm-9">
                        <select name="jabatan" id="jabatan" class="form-control" required>
                            <option value="Kajur">Ketua Jurusan</option>
                            <option value="Sekjur">Sekretaris Jurusan</option>
                            <option value="Kaprodi">Kaprodi</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Super Admin">Super Admin</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <button type="button" id="cancelButton" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('cancelButton').addEventListener('click', function() {
        window.location.href = '{{ route("home") }}';
    });
</script>
@endpush
