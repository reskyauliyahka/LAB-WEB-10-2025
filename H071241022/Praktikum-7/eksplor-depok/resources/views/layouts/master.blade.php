<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eksplor Depok - @yield('title')</title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Custom Style -->
  <style>
    body {
      background-color: #0d0d0d;
      color: #e5e5e5;
      font-family: 'Poppins', sans-serif;
    }

    /* Navbar hover animation */
    .nav-link {
      position: relative;
      transition: color 0.3s ease;
    }
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 0%;
      height: 2px;
      background-color: #00ffff;
      transition: width 0.3s ease;
    }
    .nav-link:hover::after {
      width: 100%;
    }

    /* Glassmorphism effect */
    .glass {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Alpine.js cloak */
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="min-h-screen flex flex-col">

  <!-- 🧭 Navbar -->
  <header class="glass sticky top-0 z-50 py-4 shadow-md">
    <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-6">
      <h1 class="text-2xl font-bold text-cyan-400 tracking-widest">Eksplor Depok</h1>
      <nav class="flex space-x-6 mt-3 md:mt-0">
        <a href="/" class="nav-link hover:text-cyan-300">Home</a>
        <a href="/destinasi" class="nav-link hover:text-cyan-300">Destinasi</a>
        <a href="/kuliner" class="nav-link hover:text-cyan-300">Kuliner</a>
        <a href="/galeri" class="nav-link hover:text-cyan-300">Galeri</a>
        <a href="/kontak" class="nav-link hover:text-cyan-300">Kontak</a>
      </nav>
    </div>
  </header>

  <!-- 🏙️ Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>


  <!-- 🌙 Footer -->
  <footer class="text-center py-4 border-t border-gray-800 text-gray-400">
    <p>&copy; 2025 Eksplor Depok</p>
  </footer>

</body>
</html>
