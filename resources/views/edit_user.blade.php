<!-- resources/views/edit_user.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit User</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('update-user', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Name:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">Email:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
        </div>
   
        <div class="row mb-3">
                        <label for="jabatan" class="col-sm-2 col-form-label">Role:</label>
                        <div class="col-sm-10">
                            <select name="jabatan" id="jabatanSelect" class="form-control" required>
                                <option value="">Memuat...</option>
                            </select>
                        </div>
                    </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary me-2">Update</button>
            <a href="{{ route('list-user') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route('get-jabatan') }}')
        .then(response => response.json())
        .then(data => {
            const jabatanSelect = document.getElementById('jabatanSelect');
            jabatanSelect.innerHTML = '<option value="" disabled>Pilih Role</option>'; // Reset options

            const userJabatan = '{{ $user->jabatan }}'; // Ambil nilai jabatan dari $user

            data.forEach(item => {
                if (item.nama_jabatan !== 'All') { // Tambahkan kondisi di sini
                    const option = document.createElement('option');
                    option.value = item.nama_jabatan;
                    option.textContent = item.nama_jabatan;
                    if (item.nama_jabatan === userJabatan) {
                        option.selected = true; // Tandai sebagai selected jika cocok
                    }
                    jabatanSelect.appendChild(option);
                }
            });
        })
        .catch(error => {
            console.error('Error fetching jabatan:', error);
            const jabatanSelect = document.getElementById('jabatanSelect');
            jabatanSelect.innerHTML = '<option value="">Error memuat data</option>';
        });
});
</script>
@endsection
