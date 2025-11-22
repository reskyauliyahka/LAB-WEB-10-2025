@extends('layouts.master')

@section('title', 'Destinasi')

@section('content')
<style>
  @keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .bg-animated {
    background: linear-gradient(-45deg, #000000, #111111, #1a1525, #2a1f2e);
    background-size: 400% 400%;
    animation: gradientMove 18s ease infinite;
    position: relative;
    overflow: hidden;
  }

  .bg-animated::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, #0d0d0d 0%, transparent 40%);
    z-index: 1;
  }

  .bg-animated::after {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at 30% 20%, rgba(192,132,252,0.08), transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(236,72,153,0.05), transparent 50%);
    z-index: 0;
    filter: blur(50px);
  }

  .fade-in-up {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
  }

  .fade-in-up.appear {
    opacity: 1;
    transform: translateY(0);
  }

  @media (prefers-reduced-motion: reduce) {
    .bg-animated,
    .fade-in-up {
      animation: none !important;
      transition: none !important;
      transform: none !important;
    }
  }
</style>

<section class="relative py-20 bg-animated text-gray-200 overflow-hidden">
  <div class="container mx-auto px-4 md:px-8 relative z-10">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-extrabold italic text-purple-300 tracking-wide mb-3 animate-pulse">
        Destinasi Populer di Depok
      </h2>
      <p class="text-gray-400 text-sm md:text-base">
        Temukan pesona kota Depok dari berbagai sisi — alam, budaya, dan spiritualitas.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- Situ Pengasinan -->
      <div class="fade-in-up relative group rounded-3xl overflow-hidden shadow-2xl">
        <img src="{{ asset('images/situ_pengasinan.jpg') }}" alt="Situ Pengasinan"
             class="w-full h-[380px] object-cover transition-transform duration-700 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6">
          <h3 class="text-2xl font-semibold text-purple-300 mb-2 group-hover:text-white transition">
            Situ Pengasinan
          </h3>
          <p class="text-gray-300 text-sm">
            Danau alami dengan pemandangan magis saat senja — cocok untuk healing dan foto aesthetic.
          </p>
        </div>
      </div>

      <!-- Masjid Kubah Emas -->
      <div class="fade-in-up relative group rounded-3xl overflow-hidden shadow-2xl">
        <img src="{{ asset('images/mesjid_kubah_emas.jpg') }}" alt="Masjid Kubah Emas"
             class="w-full h-[380px] object-cover transition-transform duration-700 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6">
          <h3 class="text-2xl font-semibold text-purple-300 mb-2 group-hover:text-white transition">
            Masjid Kubah Emas
          </h3>
          <p class="text-gray-300 text-sm">
            Ikon religius megah dengan arsitektur menawan yang menjadi kebanggaan warga Depok.
          </p>
        </div>
      </div>

      <!-- Taman Doa Maria -->
      <div class="fade-in-up relative group md:col-span-2 rounded-3xl overflow-hidden shadow-2xl">
        <img src="{{ asset('images/taman_doa_maria.jpg') }}" alt="Taman Doa Maria"
             class="w-full h-[420px] object-cover transition-transform duration-700 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6">
          <h3 class="text-2xl font-semibold text-purple-300 mb-2 group-hover:text-white transition">
            Taman Doa Maria
          </h3>
          <p class="text-gray-300 text-sm">
            Tempat refleksi spiritual di tengah hijaunya Cimanggis, suasananya tenang dan damai.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('appear');
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));
  });
</script>
@endsection
