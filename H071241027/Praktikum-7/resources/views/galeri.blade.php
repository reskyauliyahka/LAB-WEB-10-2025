@extends('layouts.master')

@section('content')
    <h2>Galeri Pesona Bali</h2>
    <p>Nikmati keindahan budaya dan alam Bali melalui koleksi foto kami.</p>

    <div class="gallery-grid">
        <div class="gallery-item">
            <img src="{{ asset('images/pesona1.png') }}" alt="Foto Galeri Bali 1">
        </div>
        <div class="gallery-item">
            <img src="{{ asset('images/pesona2.png') }}" alt="Foto Galeri Bali 2">
        </div>
        <div class="gallery-item">
            <img src="{{ asset('images/pesona3.png') }}" alt="Foto Galeri Bali 3">
        </div>
        <div class="gallery-item">
            <img src="{{ asset('images/pesona4.png') }}" alt="Foto Galeri Bali 4">
        </div>
        <div class="gallery-item">
            <img src="{{ asset('images/pesona5.png') }}" alt="Foto Galeri Bali 5">
        </div>
        <div class="gallery-item">
            <img src="{{ asset('images/pesona6.png') }}" alt="Foto Galeri Bali 6">
        </div>
    </div>
@endsection