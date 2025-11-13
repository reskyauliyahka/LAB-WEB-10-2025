@extends('layouts.master') @section('title', 'Selamat Datang di Enrekang') @section('content') <section class="text-center py-10 bg-white rounded-lg shadow-lg">
        <h2 class="text-4xl font-extrabold text-green-800 mb-4">
            Selamat Datang di Bumi Massenrempulu!
        </h2>
        
        <p class="text-lg text-gray-700 max-w-3xl mx-auto px-4">
            Enrekang, yang dikenal sebagai <strong>Bumi Massenrempulu</strong>, adalah sebuah kabupaten di Sulawesi Selatan yang menawarkan pesona alam pegunungan yang menakjubkan. Temukan keindahan alam, sajian kuliner khas, dan keramahan penduduk lokal di sini.
        </p>
    </section>

    <div class="mt-12 text-center">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Jelajahi Pesona Enrekang</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('destinasi') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <span class="text-5xl block mb-2">🏔️</span>
                <p class="font-semibold text-lg text-green-700">Destinasi Alam</p>
            </a>
            <a href="{{ route('kuliner') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <span class="text-5xl block mb-2">🍜</span>
                <p class="font-semibold text-lg text-green-700">Kuliner Khas</p>
            </a>
            <a href="{{ route('galeri') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <span class="text-5xl block mb-2">📸</span>
                <p class="font-semibold text-lg text-green-700">Galeri Foto</p>
            </a>
        </div>
    </div>
@endsection