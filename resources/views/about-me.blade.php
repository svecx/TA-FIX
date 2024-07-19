@extends('layouts.app')

@section('content')
<div class="navigasi" style="margin-top: 50px;">
    <div class="d-flex">
        <!-- Konten -->
        <div class="container-fluid" style="margin-left: 220px; margin-top:-40px">
            <div class="container mt-5">
                <div>
                    <img src="{{ asset('images/gambar3.png') }}" alt="Background Image" style="position:absolute; top:7%; left:700px; opacity:0.2; max-width: 110%; height: 600px">
                </div>
                <h3>About Me</h3>
                <div class="card mt-3" style="max-width: 600px; border-radius: 15px; display: flex;">
                    <div style="flex: 1; min-width: 40%;">
                        <img src="{{ asset('images/gambar4.png') }}" alt="User Image" style="width: 20%; height: auto; border-radius: 15px;">
                    </div>
                    <div class="card-body" style="flex: 2;">
                        <p class="card-title">
                            <i class="fas fa-user"></i><strong> {{ $user->name }}</strong>
                        </p>
                        <p class="card-text">
                            <i class="fas fa-envelope"></i> <strong>{{ $user->email }}</strong>
                        </p>
                        <!-- Tambahkan informasi lainnya yang ingin ditampilkan -->
                    </div>
                </div>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
