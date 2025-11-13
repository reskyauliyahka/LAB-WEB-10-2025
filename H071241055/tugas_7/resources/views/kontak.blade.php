@extends('layouts.master')

@section('title', 'Kontak Kami')

@section('content')
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 border-green-500 pb-2">Hubungi Kami</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        
        
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-4 text-green-700">Kirim Pesan</h3>
            <form action="#" method="POST">
                @csrf 
                <div class="mb-4">
                    <label for="nama" class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                    <input type="text" id="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Nama Anda">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="email@contoh.com">
                </div>
                <div class="mb-6">
                    <label for="pesan" class="block text-gray-700 font-medium mb-2">Pesan</label>
                    <textarea id="pesan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Tulis pesan Anda di sini..."></textarea>
                </div>
                <button type="submit" class="bg-green-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-800 transition duration-200 opacity-50 cursor-not-allowed" disabled>
                    Kirim (Non-fungsional)
                </button>
            </form>
        </div>
        
        
        <div class="p-8">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Informasi Kontak</h3>
            <ul class="space-y-4 text-gray-700">
                <li class="flex items-start">
                    <span class="mr-3 text-green-700 text-xl">📍</span>
                    <span>Kantor Pariwisata Kabupaten Enrekang, Sulawesi Selatan</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-3 text-green-700 text-xl">📧</span>
                    <a href="mailto:info@eksplorenrekang.com" class="text-green-600 hover:underline">info@eksplorenrekang.com</a>
                </li>
            </ul>
        </div>
        
    </div>
@endsection