@extends('layouts.master')

@section('content')

<section class="relative h-[45vh] flex items-center justify-center text-white">
    <div class="absolute inset-0 bg-cover bg-center brightness-75"
         style="background-image: url('/images/bali1.jpg');"></div>

    <h1 class="page-title reveal">Kontak 📩</h1>
</section>


<section class="py-20 px-6 md:px-20 bg-cover bg-center"
         style="background-image: url('/images/bali2.jpg'); background-attachment: fixed;">

    <div class="section-box text-white">

        <form class="grid gap-6 max-w-3xl mx-auto reveal">

            <input type="text" placeholder="Nama Lengkap" 
                   class="p-4 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white">
            
            <input type="email" placeholder="Email" 
                   class="p-4 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white">

            <textarea placeholder="Pesan..." rows="5"
                      class="p-4 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white"></textarea>

            <button class="btn-main w-full py-4 text-lg tracking-wide">
                ✉️ Kirim Pesan
            </button>

        </form>

    </div>

</section>

@endsection
