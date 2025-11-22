@extends('layouts.master')

@section('title', 'Destinasi Unggulan Enrekang')

@section('content')
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 border-green-500 pb-2">Destinasi Wisata Enrekang</h2>
    
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <x-card 
            title="Gunung Nona (Buttu Kabobong)" 
            description="Sebuah gunung yang bentuknya menyerupai organ intim wanita (bagi sebagian orang) yang membuatnya ikonik. Pemandangan dari puncaknya sangat memukau." 
            imageUrl="/images/bambapuang.jpg"
        />

        <x-card 
            title="Bukit Cekong" 
            description="Destinasi favorit untuk olahraga ekstrem seperti paralayang, arung jeram, dan panjat tebing. Menyajikan panorama lembah hijau yang luas." 
            imageUrl="/images/cekong.jpg"
        />

        <x-card 
            title="Permandian Alam Lewaja" 
            description="Kolam renang alami yang sumber airnya berasal dari pegunungan. Tempat yang cocok untuk bersantai dan menikmati udara segar Enrekang." 
            imageUrl="/images/lewaja.jpg"
        />
        
    </div>
@endsection