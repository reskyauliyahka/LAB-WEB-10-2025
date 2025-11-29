@extends('layouts.master')

@section('content')

{{-- HERO SECTION --}}
<section class="relative h-[45vh] w-full flex items-center justify-center text-white">

    {{-- Background --}}
    <div class="absolute inset-0 bg-cover bg-center brightness-75"
         style="background-image: url('/images/bali2.jpg');"></div>

    <h1 class="relative text-5xl md:text-6xl font-extrabold font-playfair text-center drop-shadow-2xl reveal">
        Destinasi Wisata Bali 🌴
    </h1>
</section>



{{-- DESTINATION SECTION --}}
<section class="py-20 px-6 md:px-20 bg-cover bg-center"
         style="background-image: url('/images/bali2.jpg'); background-attachment: fixed;">

    {{-- Background Overlay --}}
    <div class="bg-black/40 rounded-3xl p-10 backdrop-blur-xl shadow-xl">

        <h2 class="reveal text-4xl md:text-5xl font-bold font-playfair text-center text-white mb-14 drop-shadow-lg">
            ✨ Rekomendasi Tempat Wisata
        </h2>

        
        {{-- DESTINATION CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            

            {{-- CARD 1 --}}
            <div class="reveal glass rounded-3xl overflow-hidden shadow-xl hover:scale-[1.04] transition transform">
                <img src="/images/bali1.jpg" class="h-60 w-full object-cover" alt="Pura Ulun Danu Bratan">

                <div class="p-6 text-white">
                    <h3 class="font-bold text-2xl mb-3">Pura Ulun Danu Bratan</h3>
                    <p class="text-sm leading-relaxed opacity-90">
                        Ikon terkenal di Bedugul yang berdiri megah di atas Danau Bratan.
                        Suasananya tenang dengan nuansa spiritual yang kuat.
                    </p>
                </div>
            </div>


            {{-- CARD 2 --}}
            <div class="reveal glass rounded-3xl overflow-hidden shadow-xl hover:scale-[1.04] transition transform">
                <img src="/images/bali2.jpg" class="h-60 w-full object-cover" alt="Thousand Islands Nusa Penida">

                <div class="p-6 text-white">
                    <h3 class="font-bold text-2xl mb-3">Thousand Island — Nusa Penida ⛰</h3>
                    <p class="text-sm leading-relaxed opacity-90">
                        Spot Instagramable dengan tebing tinggi menghadap laut biru jernih.
                        Cocok untuk pecinta petualangan dan panorama epik.
                    </p>
                </div>
            </div>


            {{-- CARD 3 --}}
            <div class="reveal glass rounded-3xl overflow-hidden shadow-xl hover:scale-[1.04] transition transform">
                <img src="/images/bali3.jpg" class="h-60 w-full object-cover" alt="Uluwatu Bali">

                <div class="p-6 text-white">
                    <h3 class="font-bold text-2xl mb-3">Uluwatu Cliff & Kecak 🔥</h3>
                    <p class="text-sm leading-relaxed opacity-90">
                        Terkenal dengan pemandangan sunset tebing laut dan pertunjukan Tari Kecak ikonik.
                        Destinasi wajib saat berkunjung ke Bali.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- CTA SECTION --}}
<section class="py-20 bg-gray-900 text-white text-center">
    <h2 class="reveal text-4xl md:text-5xl font-bold font-playfair mb-6">
        Mau Lanjut Menjelajah?
    </h2>

    <a href="/kuliner"
       class="btn-main reveal mt-6 inline-block text-lg px-10 py-4 rounded-xl">
       🍽️ Jelajahi Kuliner Bali
    </a>
</section>

@endsection
