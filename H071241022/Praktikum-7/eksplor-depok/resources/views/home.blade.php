@extends('layouts.master')

@section('title', 'Home')

@section('content')

<style>
  /* ---HERO SLIDER--- */
  .hero-slider {
    position: relative;
    width: 100vw;
    height: 90vh;
    overflow: hidden;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
  }

  .slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transform: scale(1.05);
    transition: opacity 1.8s ease, transform 8s ease;
  }

  .slide.active {
    opacity: 1;
    transform: scale(1);
    z-index: 5;
  }

  .slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(70%) contrast(1.1);
  }

  .overlay-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.25), rgba(0,0,0,0.75));
    z-index: 6;
  }

  .hero-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    z-index: 10;
    animation: fadeUp 1.5s ease both;
  }

  .hero-text h1 {
    font-size: 3.5rem;
    font-weight: 800;
    letter-spacing: 1px;
    background: linear-gradient(90deg, #f5d36e, #f8f8f8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 200% 200%;
    animation: softShimmer 6s ease infinite;
  }

  .hero-text p {
    font-size: 1.2rem;
    color: #e5e7eb;
    margin-top: 0.8rem;
    line-height: 1.6;
  }

  .dots {
    position: absolute;
    bottom: 25px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 15;
  }

  .dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    background-color: rgba(255,255,255,0.4);
    transition: all 0.4s ease;
    cursor: pointer;
  }

  .dot:hover {
    transform: scale(1.2);
    background-color: rgba(255,255,255,0.7);
  }

  .dot.active {
    background-color: #f5d36e;
    box-shadow: 0 0 10px #f5d36eaa;
    transform: scale(1.3);
  }

  .intro {
    margin-top: 5rem;
    text-align: center;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
  }

  .intro h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #f5d36e;
    margin-bottom: 1rem;
  }

  .intro p {
    color: #cbd5e1;
    font-size: 1.1rem;
    line-height: 1.8;
  }

  /* --- CONTAINER STYLE --- */
  .section-card {
    background: rgba(40, 40, 50, 0.65);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 8px 30px rgba(0,0,0,0.4);
    transition: all 0.4s ease;
  }

  .section-card:hover {
    transform: translateY(-4px);
    background: rgba(55, 55, 65, 0.8);
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
  }

  .section-card h2 {
    font-size: 2.4rem;
    margin-bottom: 1rem;
  }

  .section-card p {
    font-size: 1.1rem;
    line-height: 1.75;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translate(-50%, -30%); }
    to { opacity: 1; transform: translate(-50%, -50%); }
  }

  @keyframes softShimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
  }
</style>

<div class="hero-slider">
  <div class="slide active">
    <img src="{{ asset('images/depok_1.jpg') }}" alt="Depok 1">
    <div class="overlay-gradient"></div>
  </div>
  <div class="slide">
    <img src="{{ asset('images/depok_2.jpg') }}" alt="Depok 2">
    <div class="overlay-gradient"></div>
  </div>
  <div class="slide">
    <img src="{{ asset('images/depok_3.jpg') }}" alt="Depok 3">
    <div class="overlay-gradient"></div>
  </div>

  <div class="hero-text">
    <h1>Eksplor Depok</h1>
    <p>Kota yang hidup dalam kenangan, rasa, dan cerita.</p>
  </div>

  <div class="dots">
    <span class="dot active" onclick="showSlide(0)"></span>
    <span class="dot" onclick="showSlide(1)"></span>
    <span class="dot" onclick="showSlide(2)"></span>
  </div>
</div>

<div class="intro">
  <h3>Selamat Datang di Eksplor Depok</h3>
  <p>
    Dari danau yang menenangkan hingga aroma soto yang menggoda — Depok selalu punya cerita untuk kamu jelajahi.
    Jelajahi keindahan, budaya, dan kuliner khas yang membuat kota ini tak terlupakan.
  </p>
</div>

<script>
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');

  function showSlide(n) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === n);
      dots[i].classList.toggle('active', i === n);
    });
    currentSlide = n;
  }

  setInterval(() => {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }, 6000);
</script>

<!-- FEATURED ESSAY SECTIONS -->
<section class="mt-24 relative px-6 md:px-16 lg:px-24 space-y-20">

  <div class="section-card">
    <h2 class="font-bold text-3xl mb-4"
    style="background: linear-gradient(90deg, #ffd86f, #ffb347);
           -webkit-background-clip: text;
           -webkit-text-fill-color: transparent;">
            Destinasi Depok
    </h2>
    <p class="text-gray-200">
      Dari lembah yang sunyi hingga jalanan yang ramai, setiap sudut Depok menyimpan kisah. 
      Kunjungi taman-taman rindang, danau yang berkilau, serta situs sejarah yang menunggu untuk diceritakan kembali. 
      Di sini, perjalanan bukan hanya tentang tempat, tetapi juga tentang perasaan yang tertinggal di setiap langkah.
    </p>
    <a href="/destinasi" class="inline-block mt-5 text-cyan-400 hover:text-white transition duration-300">→ Jelajahi lebih jauh</a>
  </div>

  <div class="section-card">
    <h2 class="font-bold text-3xl mb-4"
    style="background: linear-gradient(90deg, #ffb347, #ffcc33);
           -webkit-background-clip: text;
           -webkit-text-fill-color: transparent;">
        Kuliner Depok
    </h2>

    <p class="text-gray-200">
      Makanan adalah bahasa yang tak perlu diterjemahkan — dan di Depok, setiap gigitan bercerita. 
      Dari soto legendaris di pinggir jalan hingga kopi hangat di sudut kafe, kuliner Depok memeluk masa lalu sekaligus menyambut inovasi rasa masa kini.
    </p>
    <a href="/kuliner" class="inline-block mt-5 text-amber-400 hover:text-white transition duration-300">→ Cicipi kisah rasanya</a>
  </div>

  <div class="section-card">
    <h2 class="bg-gradient-to-r from-pink-400 to-fuchsia-500 bg-clip-text text-transparent font-bold">
      Galeri Depok
    </h2>
    <p class="text-gray-200">
      Lihat Depok dalam bingkai yang berbeda. Setiap foto adalah potongan waktu — menampilkan bagaimana kota ini 
      tumbuh, berubah, dan terus berdenyut dalam warna dan cahaya. Di Galeri, nostalgia dan modernitas berpadu indah.
    </p>
    <a href="/galeri" class="inline-block mt-5 text-pink-400 hover:text-white transition duration-300">→ Lihat galeri</a>
  </div>

  <div class="section-card">
    <h2 class="bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent font-bold">
      Kontak & Komunitas
    </h2>
    <p class="text-gray-200">
      Depok bukan hanya tentang tempat — tapi tentang orang-orangnya. 
      Hubungi kami, berbagi cerita, atau bergabung dalam komunitas yang menjaga warisan lokal dan membangun masa depan yang berkelanjutan bersama.
    </p>
    <a href="/kontak" class="inline-block mt-5 text-green-400 hover:text-white transition duration-300">→ Hubungi kami</a>
  </div>

</section>

@endsection
