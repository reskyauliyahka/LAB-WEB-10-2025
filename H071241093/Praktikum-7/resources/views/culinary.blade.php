@extends('template/master')

@section('title', $title)
@section('content')


<div class="bg-white text-gray-800 overflow-x-hidden">

  <section class="container mx-auto px-4 py-20 md:py-32">
    <div class="grid md:grid-cols-2 gap-12 items-center">
      
      <div data-aos="fade-right">
        <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 leading-tight">
          Temukan Cita Rasa Autentik
          <span class="text-blue-600">Kuliner Bau-Bau</span>
        </h1>
        <p class="mt-6 text-lg text-gray-600 max-w-lg">
          Dari segarnya kuah Ikan Parende hingga uniknya Kasuami, jelajahi warisan rasa legendaris dari jantung Pulau Buton.
        </p>
        <a href="#menu" class="mt-10 inline-block bg-blue-600 text-white text-lg font-semibold px-8 py-4 rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
          Lihat Menu Utama
        </a>
      </div>
      
      <div class="relative w-full h-[450px] md:h-[550px]" data-aos="fade-left" data-aos-delay="300">
        <svg class="absolute inset-0 w-full h-full text-blue-100" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
        </svg>

        <div class="swiper main-food-slider absolute inset-0 w-full h-full flex items-center justify-center p-12">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="{{'images/lalampa-removebg-preview.png'}}" alt="Ikan Parende" class="w-full h-auto object-cover rounded-full shadow-2xl aspect-square opacity-40">
            </div>
            <div class="swiper-slide">
              <img src="{{'images/sambal-removebg-preview.png'}}" alt="Kasuami" class="w-full h-auto object-cover rounded-full shadow-2xl aspect-square">
            </div>
            <div class="swiper-slide">
              <img src="{{'images/kambulu-removebg-preview.png'}}" alt="Sate Gogos" class="w-full h-auto object-cover rounded-full shadow-2xl aspect-square opacity-40">
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </section>

  <section class="">
    <div class="container mx-auto px-4">
      <h3 class="text-2xl font-bold text-center text-gray-900 mb-10" data-aos="fade-up">Camilan & Pelengkap Populer</h3>
      <div class="flex gap-8 overflow-x-auto pb-6" data-aos="fade-up" data-aos-delay="200">
        <div class="flex-shrink-0 flex items-center gap-4 group">
          <img src="{{'images/tuli-tuli-removebg-preview.png'}}" alt="Tuli-Tuli" class="w-20 h-20 rounded-full object-cover shadow-md transition-all duration-300 group-hover:scale-110">
          <span class="text-lg font-semibold">Tuli-Tuli</span>
        </div>
        <div class="flex-shrink-0 flex items-center gap-4 group">
          <img src="{{'images/lalampa-removebg-preview.png'}}" alt="Lalampa" class="w-20 h-20 rounded-full object-cover shadow-md transition-all duration-300 group-hover:scale-110">
          <span class="text-lg font-semibold">Lalampa</span>
        </div>
        <div class="flex-shrink-0 flex items-center gap-4 group">
          <img src="images/kambulu-removebg-preview.png" alt="Kambulu" class="w-20 h-20 rounded-full object-cover shadow-md transition-all duration-300 group-hover:scale-110">
          <span class="text-lg font-semibold">Kambulu</span>
        </div>
        <div class="flex-shrink-0 flex items-center gap-4 group">
          <img src="{{'images/suhun-removebg-preview.png'}}" alt="Sayur Suhun" class="w-20 h-20 rounded-full object-cover shadow-md transition-all duration-300 group-hover:scale-110">
          <span class="text-lg font-semibold">Sayur Suhun</span>
        </div>
        <div class="flex-shrink-0 flex items-center gap-4 group">
          <img src="{{'images/sambal-removebg-preview.png'}}" alt="Sambal Khas" class="w-20 h-20 rounded-full object-cover shadow-md transition-all duration-300 group-hover:scale-110">
          <span class="text-lg font-semibold">Sambal Khas</span>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-gray-50 py-24">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-4xl font-bold mb-4" data-aos="fade-up">Kekayaan Rasa dari Laut & Darat</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-16" data-aos="fade-up" data-aos-delay="100">
        Setiap hidangan menceritakan kisah tradisi dan kekayaan alam Buton.
      </p>
      <div class="grid md:grid-cols-3 gap-12">
        <div class="flex flex-col items-center" data-aos="fade-up" data-aos-delay="100">
          <div class="flex items-center justify-center w-24 h-24 bg-blue-100 rounded-full mb-6 ring-8 ring-blue-50">
            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.368-.662a2 2 0 01-1.265-2.063V6.25a2 2 0 00-2-2h-3.172a2 2 0 00-1.516.662l-1.9 2.533a2 2 0 01-1.32.741l-2.318.579a2 2 0 00-1.584 1.956l.042 1.849a2 2 0 001.956 1.584l2.318-.579a2 2 0 011.32-.741l1.9-2.533a2 2 0 001.516-.662h3.172a2 2 0 002 2v6.25a2 2 0 01-1.265 2.063l-2.368.662a2 2 0 00-1.022.547L10 22.25v-5.25a2 2 0 00-2-2H4.75a2 2 0 00-2 2V22l3.209-3.744a2 2 0 011.32-.741l2.318-.579a2 2 0 001.584-1.956l-.042-1.849a2 2 0 00-1.956-1.584l-2.318.579a2 2 0 01-1.32.741L4.75 16.75a2 2 0 00-1.516.662h-3.172a2 2 0 00-2 2v6.25a2 2 0 01-1.265 2.063l-2.368.662a2 2 0 00-1.022.547L0 22.25v-5.25a2 2 0 00-2-2h-3.25a2 2 0 00-2 2V22"></path></svg>
          </div>
          <h3 class="text-2xl font-semibold mb-2">Hasil Laut Segar</h3>
          <p class="text-gray-600">Terkenal dengan hasil laut terbaik, diolah langsung oleh nelayan setempat.</p>
        </div>
        <div class="flex flex-col items-center" data-aos="fade-up" data-aos-delay="200">
          <div class="flex items-center justify-center w-24 h-24 bg-blue-100 rounded-full mb-6 ring-8 ring-blue-50">
            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.747 0-3.332.477-4.5 1.253"></path></svg>
          </div>
          <h3 class="text-2xl font-semibold mb-2">Resep Warisan</h3>
          <p class="text-gray-600">Rempah-rempah lokal dan resep turun-temurun yang terjaga keasliannya.</p>
        </div>
        <div class="flex flex-col items-center" data-aos="fade-up" data-aos-delay="300">
          <div class="flex items-center justify-center w-24 h-24 bg-blue-100 rounded-full mb-6 ring-8 ring-blue-50">
            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
          </div>
          <h3 class="text-2xl font-semibold mb-2">Unik & Khas</h3>
          <p class="text-gray-600">Dari Kasuami (pengganti nasi) hingga olahan singkong yang tidak ada duanya.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="menu" class="container mx-auto px-4 py-24">
    <div class="text-center mb-16" data-aos="fade-up">
      <h2 class="text-4xl font-bold">Jelajahi Menu Utama</h2>
      <p class="text-lg text-gray-600 mt-4 max-w-2xl mx-auto">
        Ini adalah hidangan yang wajib Anda coba saat berkunjung ke Bau-Bau.
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
        <img src="{{'images/Kasuamifull.jpg'}}" alt="Kasuami" class="w-full h-56 object-cover">
        <div class="p-6">
          <h3 class="text-xl font-bold mb-2">Kasuami</h3>
          <p class="text-gray-600 text-sm">Makanan pokok pengganti nasi dari singkong parut, disajikan berbentuk tumpeng.</p>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
        <img src="{{'images/parende.jpg'}}" alt="Ikan Parende" class="w-full h-56 object-cover">
        <div class="p-6">
          <h3 class="text-xl font-bold mb-2">Ikan Parende</h3>
          <p class="text-gray-600 text-sm">Sup ikan kuah kuning segar dengan rasa asam pedas yang khas dari belimbing wuluh.</p>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
        <img src="{{'images/tulituilifull.jpg'}}" alt="Tuli-Tuli" class="w-full h-56 object-cover">
        <div class="p-6">
          <h3 class="text-xl font-bold mb-2">Tuli-Tuli</h3>
          <p class="text-gray-600 text-sm">Camilan singkong goreng gurih yang dibentuk seperti angka delapan, renyah di luar.</p>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="400">
        <img src="images/sate-gogos.jpg" alt="Sate Gogos" class="w-full h-56 object-cover">
        <div class="p-6">
          <h3 class="text-xl font-bold mb-2">Sate Gogos</h3>
          <p class="text-gray-600 text-sm">Sate kerang unik yang direbus dengan bumbu khusus lalu disajikan tanpa kuah kacang.</p>
        </div>
      </div>
    </div>
  </section>

  <section 
    class="py-32 h-[550px] bg-cover bg-center bg-fixed" 
    style="background-image: url('{{ asset('images/jokowi.jpg') }}')">
    
    <div class="container mx-auto px-4 h-full flex items-center justify-end">
      <div class="bg-white/95 md:w-1/2 p-10 md:p-14 rounded-2xl shadow-2xl backdrop-blur-sm max-w-lg" data-aos="fade-left">
        <h2 class="text-4xl font-bold text-gray-900 mb-5">Kunjungi Pasar Lokal</h2>
        <p class="text-gray-600 text-lg mb-8">
          Rasakan pengalaman autentik berbelanja bahan segar dan mencicipi jajanan tradisional langsung di pasar-pasar lokal Bau-Bau.
        </p>
        <a href="#" class="bg-blue-600 text-white text-lg font-semibold px-8 py-4 rounded-xl shadow-lg hover:bg-blue-700 transition duration-300">
          Temukan Lokasi
        </a>
      </div>
    </div>
  </section>

  <section class="bg-gray-50 py-24">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-4xl font-bold mb-16" data-aos="fade-up">Apa Kata Para Penjelajah Rasa?</h2>
      <div class="grid md:grid-cols-3 gap-8 text-left">
        <div class="bg-white p-8 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="100">
          <div class="flex items-center mb-4">
            <img src="{{'images/alsak.jpeg'}}" alt="Avatar" class="w-14 h-14 rounded-full mr-4 object-cover">
            <div>
              <h4 class="font-bold text-lg">Alif Sakti</h4>
              <p class="text-sm text-blue-600 font-medium">Wisatawan - Makassar</p>
            </div>
          </div>
          <p class="text-gray-600 italic">"Ikan Parende-nya juara! Rasa asam pedasnya benar-benar bikin kangen. Bau-Bau luar biasa."</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
          <div class="flex items-center mb-4">
            <img src="{{'images/gua1.jpeg'}}" alt="Avatar" class="w-14 h-14 rounded-full mr-4 object-cover">
            <div>
              <h4 class="font-bold text-lg">Syech Yusuf</h4>
              <p class="text-sm text-blue-600 font-medium">Food Blogger</p>
            </div>
          </div>
          <p class="text-gray-600 italic">"Pertama kali coba Kasuami, unik sekali. Teksturnya padat tapi lembut. Wajib coba kalau ke Buton."</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="300">
          <div class="flex items-center mb-4">
            <img src="{{'images/doyo.jpeg'}}" alt="Avatar" class="w-14 h-14 rounded-full mr-4 object-cover">
            <div>
              <h4 class="font-bold text-lg">Nurhidayat N</h4>
              <p class="text-sm text-blue-600 font-medium">Turis - Jakarta</p>
            </div>
          </div>
          <p class="text-gray-600 italic">"Tuli-Tuli-nya enak buat ngemil sore di Pantai Nirwana. Gurih dan harganya murah meriah."</p>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-blue-600 py-24">
    <div class="container mx-auto px-4 text-center" data-aos="zoom-in-up">
      <h2 class="text-4xl font-bold text-white mb-6">Siap Menjelajahi Rasa?</h2>
      <p class="text-lg text-blue-100 max-w-xl mx-auto mb-10">
        Rencanakan perjalanan wisata kuliner Anda di Bau-Bau dan temukan cita rasa yang tak akan terlupakan.
      </p>
      <a href="#" class="bg-white text-blue-600 text-lg font-bold px-10 py-4 rounded-xl shadow-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105">
        Mulai Rencana
      </a>
    </div>
  </section>
</div>

@endsection
