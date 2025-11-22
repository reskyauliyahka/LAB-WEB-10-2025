<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Ucup | @yield('title')</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script>
        tailwind.config = {
            theme: {
                extend: {
                }
            },
            plugins: [
                require('@tailwindcss/forms'),
            ],
        }
    </script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<style>
   
    .line-clamp-3 {
      overflow: hidden;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 3;
    }
    .line-clamp-4 {
      overflow: hidden;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 4;
    }
  </style>
</head>
<body>
    {{-- <h1>@yield('title')</h1> --}}
    <x-navbar/>
    
    <div>
        @yield('content')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    
    <x-footer/>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  
  <script>
    // Inisialisasi AOS
    AOS.init({
      duration: 800,
      once: true,
      offset: 50,
    });

    
  </script>
</body>
</html>