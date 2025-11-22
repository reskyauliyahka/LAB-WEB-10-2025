@extends('layouts.master')
@section('title', 'Home')
@section('content')
<section class="hero">
<h2>Selamat Datang!</h2>
<p>{{ $intro }}</p>
</section>


<section class="highlight">
<h3>Rekomendasi Singkat</h3>
<div class="grid">
<x-card image="{{ asset('images/prambanan.jpg') }}" title="Candi Prambanan">Candi Hindu megah dan penuh sejarah.</x-card>
<x-card image="{{ asset('images/gudeg.jpg') }}" title="Gudeg">Cicipi makanan khas Yogyakarta yang manis dan gurih.</x-card>
<x-card image="{{ asset('images/keraton.jpg') }}" title="Keraton">Pusat budaya Jawa yang hidup hingga saat ini.</x-card>
</div>
</section>
@endsection