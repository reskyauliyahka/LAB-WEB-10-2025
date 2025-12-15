@extends('layouts.master')

@section('content')
    
    <div class="hero-section">
        <div class="hero-overlay"></div> <div class="hero-content">
            <h2>Selamat Datang di Eksplor Bali</h2>
            <p>
                Pulau Dewata, surga tropis yang memadukan keindahan alam,
                budaya yang kaya, dan pura yang megah.
            </p>
            <div>
                <a href="/destinasi" class="cta-button">Lihat Destinasi</a>
                <a href="/kuliner" class="cta-button secondary">Cicipi Kuliner</a>
            </div>
        </div>
    </div>

    <div style="text-align: center; max-width: 800px; margin: 2rem auto;">
        <h3>Tentang Website Ini</h3>
        <p>
            Website ini adalah panduan Anda untuk menemukan destinasi wisata terbaik, 
            kuliner khas yang wajib dicoba, dan galeri pesona Bali yang tak terlupakan. 
            Jelajahi setiap halaman untuk merencanakan petualangan Anda!
        </p>
    </div>

@endsection