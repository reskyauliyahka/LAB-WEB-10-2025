@extends('layouts.master')

@section('content')

{{-- HERO --}}
<section class="relative h-[45vh] flex items-center justify-center text-white">
    <div class="absolute inset-0 bg-cover bg-center brightness-75"
         style="background-image: url('/images/kuliner1.jpg');"></div>

    <h1 class="page-title reveal">Kuliner Khas Bali 🍽️</h1>
</section>

{{-- CONTENT --}}
<section class="py-20 px-6 md:px-20 bg-cover bg-center"
         style="background-image: url('/images/bali2.jpg'); background-attachment: fixed;">

    <div class="section-box text-white">

        <h2 class="text-4xl md:text-5xl font-playfair font-bold text-center mb-14 reveal">
            Rekomendasi Makanan Terbaik Bali
        </h2>

        {{-- GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

            {{-- Card 1 --}}
            <x-food-card 
                img="/images/kuliner1.jpg"
                title="Sate Lilit"
                desc="Sate khas Bali berbahan ikan atau ayam yang dicampur rempah aromatik, dibakar dan disajikan dengan bumbu sambal matah."
            />

            {{-- Card 2 --}}
            <x-food-card 
                img="/images/kuliner2.jpg"
                title="Ayam Betutu"
                desc="Ayam berbumbu pedas khas Gilimanuk. Dibungkus daun pisang, dimasak perlahan hingga menghasilkan aroma smoky gurih."
            />

            {{-- Card 3 --}}
            <x-food-card 
                img="/images/kuliner3.jpg"
                title="Seafood Jimbaran"
                desc="Hidangan laut segar dengan saus khas Bali, disantap sambil menikmati sunset Jimbaran Beach — pengalaman makan yang magis."
            />

        </div>

        {{-- CTA --}}
        <div class="text-center mt-16 reveal">
            <a href="/galeri" class="btn-main px-10 py-4 text-lg">
                📸 Lihat Galeri Wisata
            </a>
        </div>

    </div>

</section>

@endsection
