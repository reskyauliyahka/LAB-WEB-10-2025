@extends('layouts.master')

@section('title', 'Galeri')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&display=swap');

  @keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .bg-animated {
    background: linear-gradient(-45deg, #0a0a0a, #111827, #1e293b, #0a0a0a);
    background-size: 300% 300%;
    animation: gradientMove 12s ease infinite;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .hero-title {
    font-family: 'Playfair Display', serif;
    font-style: italic;
    font-size: 4rem;
    background: linear-gradient(90deg, #67e8f9, #a5f3fc, #67e8f9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 1px;
    text-shadow: 0 0 15px rgba(103, 232, 249, 0.15);
  }

  .hero-sub {
    color: #cbd5e1;
    font-size: 1.1rem;
    font-style: italic;
  }

  html {
    scroll-behavior: smooth;
  }
</style>

<!-- Hero Section -->
<section class="bg-animated text-white">
  <div>
    <h1 class="hero-title drop-shadow-xl">Galeri Depok</h1>
    <p class="hero-sub mt-4">Menelusuri keindahan dan kehidupan di kota Depok</p>
    <a href="#gallery" class="mt-8 inline-block bg-cyan-500 hover:bg-cyan-400 text-black font-semibold px-6 py-3 rounded-full transition-all duration-300">
      Jelajahi Galeri ↓
    </a>
  </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="py-16 px-6 md:px-16 bg-black text-white">
  <h2 class="text-3xl font-bold text-cyan-400 mb-10 text-center">Galeri Eksplor Depok</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @for ($i = 1; $i <= 12; $i++)
      <div class="fade-up">
        <img src="{{ asset("images/depok_$i.jpg") }}" 
              
             class="rounded-2xl shadow-lg hover:scale-105 hover:shadow-cyan-500/30 transition duration-500 ease-in-out w-full h-64 object-cover">
      </div>
    @endfor
  </div>
</section>

<script>
  const fadeElements = document.querySelectorAll('.fade-up');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, { threshold: 0.2 });

  fadeElements.forEach(el => observer.observe(el));
</script>
@endsection
