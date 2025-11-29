@extends('layouts.master')

@section('content')

<section class="relative h-[45vh] flex items-center justify-center text-white">
    <div class="absolute inset-0 bg-cover bg-center brightness-75"
         style="background-image: url('/images/bali3.jpg');"></div>

    <h1 class="page-title reveal">Galeri 📸</h1>
</section>


<section class="py-20 px-6 md:px-20 bg-cover bg-center"
         style="background-image: url('/images/bali2.jpg'); background-attachment: fixed;">

    <div class="section-box">

        <h2 class="text-white text-4xl md:text-5xl font-bold font-playfair text-center mb-10 reveal">
            Keindahan Bali Dalam Gambar
        </h2>

        {{-- Grid Gallery --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 reveal">
            @foreach(['bali1.jpg','bali2.jpg','bali3.jpg','kuliner1.jpg','kuliner2.jpg','kuliner3.jpg'] as $img)
                <img src="/images/{{ $img }}" class="rounded-2xl shadow-xl hover:scale-105 transition">
            @endforeach
        </div>

    </div>

</section>

@endsection
