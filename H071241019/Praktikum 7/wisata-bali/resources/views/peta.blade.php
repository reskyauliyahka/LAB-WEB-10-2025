@extends('layouts.master')

@section('content')

<section class="relative h-[45vh] flex items-center justify-center text-white">
    <div class="absolute inset-0 bg-cover bg-center brightness-75"
         style="background-image: url('/images/bali1.jpg');"></div>

    <h1 class="page-title reveal">Peta Wisata Bali 🗺️</h1>
</section>


<section class="py-20 px-6 md:px-20 bg-cover bg-center"
         style="background-image: url('/images/bali2.jpg'); background-attachment: fixed;">

    <div class="section-box text-white space-y-8">

        <p class="text-lg text-center max-w-2xl mx-auto reveal">
            Temukan lokasi wisata populer Bali melalui peta interaktif berikut. Zoom, geser, dan jelajahi!
        </p>

        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31547.97727290548!2d115.1606917!3d-8.7237662!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd240e4cf5d1bd7%3A0x7d37e84bddedd223!2sKuta%2C%20Badung%20Regency%2C%20Bali!5e0!3m2!1sen!2sid!4v1700000000000"
            class="w-full h-[500px] rounded-2xl shadow-2xl reveal"
            allowfullscreen="">
        </iframe>

    </div>

</section>

@endsection
