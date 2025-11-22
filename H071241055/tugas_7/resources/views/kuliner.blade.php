@extends('layouts.master')

@section('title', 'Kuliner Khas Enrekang')

@section('content')
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 border-green-500 pb-2">Jejak Rasa Khas Massenrempulu</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <x-card 
            title="Dangke" 
            description="Produk olahan susu kerbau atau sapi yang bentuknya mirip keju. Rasanya gurih dan sedikit asin, sering dimakan dengan nasi panas atau digoreng." 
            imageUrl="/images/dangke.jpg" 
        />

        <x-card 
            title="Nasu Cemba" 
            description="Masakan daging berkuah khas Enrekang yang menggunakan daun cemba (sejenis kecombrang) sebagai bumbu utama, memberikan rasa asam segar yang unik." 
            imageUrl="/images/nasu_cemba.jpg" 
        />

        <x-card 
            title="Deppa Tetekan" 
            description="Kue tradisional khas Enrekang yang terbuat dari tepung beras dan gula merah cair. Disebut 'tetekan' (ditekan) karena proses pembuatannya yang ditekan-tekan." 
            imageUrl="/images/deppa_tetekan.jpg" 
        />
        
    </div>

@endsection