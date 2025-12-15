@extends('layouts.master')

@section('content')
    <h2>Kuliner Khas Bali</h2>
    <p>Jangan lewatkan cita rasa otentik dari makanan khas Pulau Dewata.</p>

    <div class="card-container">
        <x-card 
            judul="Babi Guling" 
            gambar="babi.png" 
            deskripsi="Hidangan babi guling utuh (base genep), disajikan dengan nasi, sayur lawar, dan kulit yang sangat renyah." 
        />
        
        <x-card 
            judul="Ayam Betutu" 
            gambar="ayam.png" 
            deskripsi="Ayam utuh yang dimasak lambat (8-12 jam) dengan bumbu betutu kaya rempah, dibungkus daun pisang hingga sangat empuk." 
        />
        
        <x-card 
            judul="Sate Lilit" 
            gambar="sate.png" 
            deskripsi="Daging cincang (ikan/ayam) yang dicampur kelapa parut dan bumbu, lalu dililitkan pada batang serai dan dibakar." 
        />
    </div>
@endsection