@extends('layouts.master')

@section('title', 'Galeri Foto Enrekang')

@section('content')
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 border-green-500 pb-2">Galeri Keindahan Enrekang</h2>
    
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        
        <div class="overflow-hidden rounded-lg shadow-md aspect-w-1 aspect-h-1">
            <img src="/images/galeri_1.jpg" alt="Galeri Enrekang 1" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
        </div>
        <div class="overflow-hidden rounded-lg shadow-md aspect-w-1 aspect-h-1">
            <img src="/images/galeri_2.jpg" alt="Galeri Enrekang 2" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
        </div>
        <div class="overflow-hidden rounded-lg shadow-md aspect-w-1 aspect-h-1">
            <img src="/images/galeri_3.jpg" alt="Galeri Enrekang 3" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
        </div>
        <div class="overflow-hidden rounded-lg shadow-md aspect-w-1 aspect-h-1">
            <img src="/images/galeri_4.jpg" alt="Galeri Enrekang 4" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
        </div>
        
    </div>
@endsection