<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Fishit Simulator')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root{
            --deep-1: #011627;
            --deep-2: #02293a;
            --accent: #37b6ff;
            --glass: rgba(255,255,255,0.06);
        }

        html,body {
            height:100%; margin:0;
            font-family: 'Poppins', sans-serif;
            background:var(--deep-1);
            color: #e6f2ff;
        }

        .ocean-bg{
            position:fixed;
            inset:0;
            background: url('/images/ocean_bg.jpg') center center / cover no-repeat;
            filter: brightness(0.65) contrast(1.1);
            z-index:0;
        }

        .sun-rays{
            position:fixed;
            inset:0;
            background: url('/images/ocean_rays.png') center top / cover no-repeat;
            opacity: 0.25;
            z-index:1;
            pointer-events:none;
            mix-blend-mode: screen;
        }

        .particles{
            position:fixed;
            inset:0;
            background: url('/images/particle.png') repeat;
            opacity:0.06;
            z-index:2;
            pointer-events:none;
            animation: drift 40s linear infinite;
        }
        @keyframes drift {
            from{transform:translateY(0)}
            to{transform:translateY(-200px);}
        }

        nav.navbar {
            background: linear-gradient(90deg, rgba(2,41,58,0.6), rgba(2,41,58,0.4));
            border-bottom: 1px solid rgba(255,255,255,0.03);
            z-index: 5;
        }

        .brand {
            font-weight:600; 
            color:#dff6ff; 
            letter-spacing:0.4px;
        }

        .scene-layer{
            position:fixed; inset:0;
            pointer-events:none;
            z-index:3;
        }

        .boat{
            position:absolute;
            left:50%; top:6%;
            transform:translateX(-50%);
            width:360px;
            opacity:0.92;
        }

        .swimmer{
            position:absolute;
            width:140px;
            z-index:2;
            opacity:0.9;
            will-change: transform;
        }

        .swim-slow { animation: swim-left-right 20s linear infinite; }
        .swim-slower { animation: swim-left-right 30s linear infinite; }
        .swim-fast { animation: swim-left-right 13s linear infinite; }

        @keyframes swim-left-right {
            0% { transform: translateX(-20vw) translateY(0) scaleX(1); }
            50% { transform: translateX(110vw) translateY(-18px) scaleX(1); }
            100% { transform: translateX(-20vw) translateY(0) scaleX(1); }
        }

        .content-wrap{
            position:relative;
            z-index:4;
            padding: 2.5rem 1rem;
            min-height: calc(100vh - 80px);
        }

        .card-glass{
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.6);
            backdrop-filter: blur(6px) saturate(120%);
            color: #eaf7ff;
        }
        .fish-photo{
            width:120px;
            height:80px;
            object-fit:cover;
            border-radius:8px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.5);
        }

        .fish-grid{
            display:grid;
            grid-template-columns: repeat(auto-fill, minmax(320px,1fr));
            gap:18px;
        }

        .muted { color: rgba(230,242,255,0.7); font-size:0.9rem; }
        .rarity { font-weight:600; color:#fff; padding:4px 8px; border-radius:10px; font-size:0.85rem; background: rgba(0,0,0,0.18); }

        .card-glass:hover {
            transform: translateY(-6px);
            transition: all .25s ease;
        }

        @media (max-width:576px){
            .boat{ display:none; }
            .fish-photo{ width:100px; height:66px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="ocean-bg"></div>
    <div class="sun-rays"></div>
    <div class="particles"></div>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="brand navbar-brand" href="{{ route('fishes.index') }}">Fishit Simulator</a>
            <div class="ms-auto">
                <a href="{{ route('fishes.create') }}" class="btn btn-outline-light btn-sm">+ Tambah Ikan</a>
            </div>
        </div>
    </nav>

    <div class="scene-layer">
        <img src="/images/boat.png" class="boat" alt="boat">

        <img src="/images/fish_realistic_2.png" class="swimmer swim-slower" style="top:45%; left:-20%;" alt="swim1">
        <img src="/images/fish_realistic_2.png" class="swimmer swim-slow" style="top:60%; left:-40%; width:180px;" alt="swim2">
    </div>

    <div class="content-wrap container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>

    <!-- parallax movement -->
    <script>
        const scene = document.querySelector('.scene-layer');
        if(scene) {
            document.addEventListener('mousemove', (e) => {
                const x = (e.clientX / window.innerWidth - 0.5) * 10;
                const y = (e.clientY / window.innerHeight - 0.5) * 6;
                scene.style.transform = `translate3d(${x}px, ${y}px, 0)`;
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
