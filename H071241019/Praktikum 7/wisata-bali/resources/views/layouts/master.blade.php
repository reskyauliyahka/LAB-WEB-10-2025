<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Bali</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:wght@600;800&display=swap" rel="stylesheet">

    {{-- Tailwind Custom --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        playfair: ['Playfair Display', 'serif'],
                    },
                    animation:{
                        fadeUp: "fadeUp 1s ease-out forwards",
                    },
                    keyframes:{
                        fadeUp:{
                            '0%':{opacity:0, transform:'translateY(20px)'},
                            '100%':{opacity:1, transform:'translateY(0)'},
                        }
                    }
                }
            }
        }
    </script>

    {{-- UI Custom CSS --}}
    <style>
<style>
    /* Global content wrapper aesthetic */
    section {
        scroll-margin-top: 120px;
    }

    /* Image hover smooth effect */
    img {
        transition: .4s ease;
    }
    img:hover {
        transform: scale(1.04);
        filter: brightness(1.05);
    }

    /* Global title style */
    .page-title {
        @apply text-5xl md:text-6xl font-extrabold font-playfair text-center drop-shadow-2xl mb-10;
    }

    /* Section box wrapper */
    .section-box {
        @apply bg-black/40 backdrop-blur-xl rounded-3xl p-10 shadow-xl border border-white/20;
    }

    /* Floating Action Button */
    .fab {
        @apply fixed bottom-20 right-6 bg-gradient-to-r from-red-500 to-orange-400 
        text-white p-4 rounded-full shadow-xl hover:scale-110 transition cursor-pointer;
    }

    /* Dark Mode Text Fix */
    body.dark h1,
    body.dark h2,
    body.dark h3,
    body.dark p,
    body.dark a {
        color: white !important;
    }
</style>


</style>


</head>

<body class="font-inter tracking-wide leading-relaxed selection:bg-yellow-300 selection:text-black">

    {{-- NAVBAR --}}
    <nav class="glass fixed top-6 left-1/2 -translate-x-1/2 w-[92%] md:w-[70%] px-8 py-4 
            shadow-xl rounded-2xl z-50 flex justify-between items-center">

        <h1 class="text-white font-playfair text-3xl font-bold tracking-wide drop-shadow-md">
            Explore Bali ✨
        </h1>

        {{-- Desktop Menu --}}
        <div class="hidden md:flex gap-6 text-white font-medium text-lg">
            <a class="hover:text-yellow-300 transition" href="/">Home</a>
            <a class="hover:text-yellow-300 transition" href="/destinasi">Destinasi</a>
            <a class="hover:text-yellow-300 transition" href="/kuliner">Kuliner</a>
            <a class="hover:text-yellow-300 transition" href="/galeri">Galeri</a>
            <a class="hover:text-yellow-300 transition" href="/peta">Peta Wisata</a>
            <a class="hover:text-yellow-300 transition" href="/kontak">Kontak</a>
        </div>

        {{-- MOBILE BUTTON --}}
        <div class="flex gap-4 items-center">
            <button onclick="toggleMenu()" class="md:hidden text-white text-3xl hover:scale-110 transition">
                ☰
            </button>

            {{-- Dark Mode Toggle --}}
            <button onclick="toggleDarkMode()" id="darkToggle" 
                class="text-white text-2xl hover:scale-110 transition">
                🌙
            </button>
        </div>

    </nav>

    {{-- MOBILE MENU --}}
    <div id="mobile-menu" class="hidden glass fixed top-24 left-1/2 -translate-x-1/2 
        w-[92%] px-8 py-6 rounded-2xl shadow-xl z-[60] text-white text-lg space-y-4">

        <a href="/" class="block hover:text-yellow-300 transition">Home</a>
        <a href="/destinasi" class="block hover:text-yellow-300 transition">Destinasi</a>
        <a href="/kuliner" class="block hover:text-yellow-300 transition">Kuliner</a>
        <a href="/galeri" class="block hover:text-yellow-300 transition">Galeri</a>
        <a href="/peta" class="block hover:text-yellow-300 transition">Peta Wisata</a>
        <a href="/kontak" class="block hover:text-yellow-300 transition">Kontak</a>
    </div>


    {{-- PAGE CONTENT --}}
    <main class="min-h-screen pb-32">
        @yield('content')
    </main>
    
    {{-- Floating Button --}}
    <div onclick="window.scrollTo({top:0,behavior:'smooth'})" class="fab">
        ⬆️
    </div>

    {{-- FOOTER --}}
    <footer class="glass backdrop-blur-xl py-6 text-white text-center font-semibold shadow-xl 
           fixed bottom-0 left-1/2 -translate-x-1/2 w-[90%] md:w-[70%] rounded-xl mb-4">

        © {{ date('Y') }} Explore Bali — Made with ❤️ for Amazing Experience 🇮🇩
    </footer>


    {{-- Scripts --}}
    <script>
        function toggleMenu(){
            document.getElementById("mobile-menu").classList.toggle("hidden");
        }

        function toggleDarkMode(){
            let body = document.body;
            let icon = document.getElementById("darkToggle");
            body.classList.toggle("dark");
            icon.textContent = body.classList.contains('dark') ? '☀️' : '🌙';
        }

        window.addEventListener("scroll", () => {
            document.querySelectorAll(".reveal").forEach(el => {
                if(el.getBoundingClientRect().top < window.innerHeight - 120){
                    el.classList.add("active");
                }
            });
        });
    </script>

</body>
</html>
