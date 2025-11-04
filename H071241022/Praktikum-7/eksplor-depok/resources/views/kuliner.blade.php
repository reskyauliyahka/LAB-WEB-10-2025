@extends('layouts.master')

@section('title', 'Kuliner')

@section('content')

<style>
  body {
    background-color: #0a0a0a;
    color: #e5e7eb;
  }

  .section-bg {
    background: linear-gradient(135deg, #000000, #1a1625, #231b2e);
    position: relative;
    padding: 6rem 0;
    overflow: hidden;
  }

  .section-bg::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at 25% 20%, rgba(147,51,234,0.1), transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(236,72,153,0.07), transparent 50%);
    pointer-events: none;
    z-index: 0;
    filter: blur(60px);
  }

  .section-title {
    text-align: center;
    font-size: 2.75rem;
    font-weight: 700;
    margin-bottom: 4rem;
    background: linear-gradient(90deg, #a855f7, #ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .glass-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 1.25rem;
    overflow: hidden;
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
  }

  .glass-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.6);
  }

  .glass-card img {
    width: 100%;
    height: 380px;
    object-fit: cover;
    transition: transform 0.6s ease;
  }

  .glass-card:hover img {
    transform: scale(1.05);
  }

  .card-text {
    padding: 1.75rem;
  }

  .card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #c084fc;
    margin-bottom: 0.75rem;
  }

  .card-desc {
    color: #d1d5db;
    line-height: 1.7;
  }

  /* Fade-up animation */
  .fade-up {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s ease;
  }

  .fade-up.visible {
    opacity: 1;
    transform: translateY(0);
  }

  @media (max-width: 768px) {
    .grid-cols-2 {
      grid-template-columns: 1fr !important;
    }

    .glass-card img {
      height: 280px;
    }
  }
</style>

<section class="section-bg">
  <div class="container mx-auto px-4 md:px-8 relative z-10">

    <h1 class="section-title italic tracking-wide">Kuliner Khas Depok</h1>
    <p class="text-center text-gray-400 text-base md:text-lg -mt-6 mb-12 italic">
        Cita rasa lokal yang memadukan tradisi, kehangatan, dan kenangan setiap suapnya.
    </p>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

      <!-- 1️⃣ Dodol Depok -->
      <div class="glass-card fade-up">
        <img src="{{ asset('images/dodol_depok.jpg') }}" alt="Dodol Depok">
        <div class="card-text">
          <h2 class="card-title">Dodol Depok</h2>
          <p class="card-desc">
            Rasa legit dan nostalgia dari jajanan klasik yang tak lekang oleh waktu. 
            Dodol Depok adalah simbol manisnya masa lalu yang tetap dicintai hingga kini.
          </p>
        </div>
      </div>

      <!-- 2️⃣ Es Cincau -->
      <div class="glass-card fade-up">
        <img src="{{ asset('images/es_cincau.jpg') }}" alt="Es Cincau">
        <div class="card-text">
          <h2 class="card-title">Es Cincau</h2>
          <p class="card-desc">
            Sensasi segar dari cincau hitam berpadu dengan santan dan gula merah. 
            Es Cincau bukan hanya minuman, tapi perasaan dingin yang menenangkan di tengah panasnya Depok.
          </p>
        </div>
      </div>

      <!-- 3️⃣ Soto Depok -->
      <div class="glass-card fade-up">
        <img src="{{ asset('images/soto_depok2.jpg') }}" alt="Soto Depok">
        <div class="card-text">
          <h2 class="card-title">Soto Depok</h2>
          <p class="card-desc">
            Hangat, gurih, dan penuh rempah — soto khas Depok adalah pelukan rasa yang bikin rindu rumah setiap sendoknya.
          </p>
        </div>
      </div>

      <!-- 4️⃣ Sayur Gabus Pucung -->
      <div class="glass-card fade-up">
        <img src="{{ asset('images/sayur_gabus.jpg') }}" alt="Sayur Gabus Pucung">
        <div class="card-text">
          <h2 class="card-title">Sayur Gabus Pucung</h2>
          <p class="card-desc">
            Masakan khas Betawi yang juga populer di Depok, menggunakan ikan gabus yang dimasak dengan kuah hitam pekat dari kluwek.
          </p>
        </div>
      </div>

    </div>

    <div class="text-center mt-16 fade-up">
      <p class="text-gray-400 italic text-lg">
        Ingin mencicipi semuanya? Jelajahi kuliner malam dan pasar tradisional Depok — setiap rasa punya cerita.
      </p>
    </div>

  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.fade-up');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
      });
    }, { threshold: 0.2 });
    elements.forEach(el => observer.observe(el));
  });
</script>

@endsection
