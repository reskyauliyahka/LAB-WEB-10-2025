@extends('layouts.master')

@section('content')
    <h2>Destinasi Wisata Unggulan di Bali</h2>

    <div class="card-container">
        <x-card 
            judul="Pura Luhur Uluwatu" 
            gambar="pulau.png"
            deskripsi="Pura laut di atas tebing curam 70 meter, tempat terbaik untuk menikmati sunset dan Tari Kecak." 
        />
        
        <x-card 
            judul="Pura Tanah Lot" 
            gambar="tanahlot.png" 
            deskripsi="Pura di atas batu karang di tengah laut. Pemandangannya sangat dramatis, terutama saat air pasang." 
        />
        
        <x-card 
            judul="Sawah Terasering Tegalalang" 
            gambar="sawah.png" 
            deskripsi="Pemandangan sawah berundak yang hijau dan indah di Ubud, menggunakan sistem irigasi 'subak'." 
        />
    </div>
@endsection