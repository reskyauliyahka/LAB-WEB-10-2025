@extends('layouts.master')

@section('title', 'Kontak')

@section('content')

<style>
  @keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .bg-animated {
    background: linear-gradient(-45deg, #000000, #1a1625, #231b2e, #000000);
    background-size: 400% 400%;
    animation: gradientMove 18s ease infinite;
    position: relative;
    overflow: hidden;
    min-height: 100vh;
  }

  .bg-animated::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at 25% 25%, rgba(192,132,252,0.08), transparent 40%),
      radial-gradient(circle at 80% 75%, rgba(236,72,153,0.06), transparent 40%);
    z-index: 0;
    filter: blur(60px);
  }

  .glass {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1.25rem;
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
  }
</style>

<section class="bg-animated flex flex-col items-center justify-center py-20 text-gray-200 relative">
  <div class="container mx-auto px-4 relative z-10">

    <h2 class="text-4xl font-extrabold italic text-purple-300 tracking-wide text-center mb-3 animate-pulse">
      Hubungi Kami
    </h2>
    <p class="text-center text-gray-400 text-base md:text-lg mb-10 italic">
      Kami senang mendengar cerita, masukan, atau pertanyaan darimu. Mari terhubung.
    </p>

    <form class="max-w-md mx-auto glass p-8 rounded-2xl space-y-5">
      <input type="text" placeholder="Nama Lengkap"
        class="w-full bg-transparent border border-gray-700 rounded-lg p-3 text-gray-200 focus:ring-2 focus:ring-purple-400 outline-none transition">
      
      <input type="email" placeholder="Email"
        class="w-full bg-transparent border border-gray-700 rounded-lg p-3 text-gray-200 focus:ring-2 focus:ring-purple-400 outline-none transition">
      
      <textarea placeholder="Pesanmu" rows="4"
        class="w-full bg-transparent border border-gray-700 rounded-lg p-3 text-gray-200 focus:ring-2 focus:ring-purple-400 outline-none transition"></textarea>
      
      <button
        class="w-full bg-gradient-to-r from-purple-400 to-pink-400 hover:from-purple-300 hover:to-pink-300 text-black font-semibold py-3 rounded-lg transition">
        Kirim Pesan
      </button>
    </form>

  </div>
</section>

@endsection
