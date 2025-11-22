<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eksplor Enrekang')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-gray-50 font-sans">

    
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-green-700">ENREKANG PESONA</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-green-700 font-medium">Home</a></li>
                    <li><a href="{{ route('destinasi') }}" class="text-gray-600 hover:text-green-700 font-medium">Destinasi</a></li>
                    <li><a href="{{ route('kuliner') }}" class="text-gray-600 hover:text-green-700 font-medium">Kuliner</a></li>
                    <li><a href="{{ route('galeri') }}" class="text-gray-600 hover:text-green-700 font-medium">Galeri</a></li>
                    <li><a href="{{ route('kontak') }}" class="text-gray-600 hover:text-green-700 font-medium">Kontak</a></li>
                </ul>
            </nav>
        </div>
    </header>

    
    <main class="container mx-auto px-4 py-8 min-h-screen">
        @yield('content') 
    </main>

    
    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="container mx-auto px-4 text-center">
            &copy; 2025 Eksplor Enrekang. All Rights Reserved. | Tugas Praktikum 7
        </div>
    </footer>

</body>
</html>