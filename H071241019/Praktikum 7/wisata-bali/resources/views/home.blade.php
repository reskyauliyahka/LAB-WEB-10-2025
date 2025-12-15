@extends('layouts.master')

@section('content')

<section class="hero-bg h-screen w-full bg-cover bg-center" style="background-image: url('/images/bali1.jpg')">
    <div class="bg-black/45 w-full h-full flex items-center px-10 md:px-24">
        <div class="text-white max-w-2xl animation fadeUp">
            <h1 class="font-playfair text-6xl md:text-7xl font-extrabold drop-shadow-lg">
                Selamat Datang di Bali 🌴
            </h1>

            <p class="mt-6 text-xl md:text-2xl font-light leading-relaxed drop-shadow">
                Surga tropis dengan pantai eksotis, budaya mendalam, dan kuliner yang menggugah.
            </p>

            <a href="/destinasi" class="btn-main mt-10 inline-block">
                Mulai Jelajah →
            </a>
        </div>
    </div>
</section>

@endsection
