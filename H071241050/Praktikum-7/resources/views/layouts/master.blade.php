<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Eksplor Pariwisata Nusantara - @yield('title')</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<header class="site-header">
<div class="container">
<h1 class="logo">Eksplor Pariwisata Nusantara</h1>
<nav class="nav">
<a href="{{ url('/') }}">Home</a>
<a href="{{ url('/destinasi') }}">Destinasi</a>
<a href="{{ url('/kuliner') }}">Kuliner</a>
<a href="{{ url('/galeri') }}">Galeri</a>
<a href="{{ url('/kontak') }}">Kontak</a>
</nav>
</div>
</header>


<main class="container">
@yield('content')
</main>


<footer class="site-footer">
<div class="container">&copy; {{ date('Y') }} Eksplor Pariwisata Nusantara. All rights reserved.</div>
</footer>
</body>
</html>