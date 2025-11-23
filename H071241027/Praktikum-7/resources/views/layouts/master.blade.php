<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eksplor Pariwisata Bali</title>
    
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            background-color: #f8f9fa; 
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1140px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        header {
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 1.75rem;
            color: #212529;
            font-weight: 600;
        }
        nav a {
            margin-left: 1.5rem;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: color 0.2s;
        }
        nav a:hover, nav a.active { 
            color: #007bff; 
        }
        
        .content {
            min-height: 60vh; 
        }
        .content h2 {
            font-size: 2rem;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
        }
        
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem; 
        }
        
        .card {
            background: #ffffff;
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
            overflow: hidden; 
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px); 
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }
        .card img {
            width: 100%;
            height: 220px; 
            object-fit: cover; 
            display: block;
        }
        .card-content {
            padding: 1.5rem;
        }
        .card-content h3 {
            margin-top: 0;
            margin-bottom: 0.75rem;
            font-size: 1.35rem;
            font-weight: 600;
        }
        .card-content p {
            margin-bottom: 0;
            font-size: 0.95rem;
            color: #444;
        }
        
       
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }
        .gallery-item {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .gallery-item:hover {
            transform: scale(1.03); 
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }
        
        .contact-form {
            max-width: 600px;
            margin: 0 auto; 
            background: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box; 
            font-family: inherit;
            font-size: 1rem;
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        .form-button {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .form-button:hover {
            background: #0056b3;
        }

        .hero-section {
            position: relative; 
            height: 60vh; 
            min-height: 450px;
            background-image: url('{{ asset('images/tanahlot.png') }}'); 
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #ffffff;
            margin-bottom: 2rem;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4); 
            z-index: 1;
        }

        .hero-content {
            position: relative; 
            z-index: 2;
            padding: 2rem;
        }

        .hero-content h2 {
            font-size: 2.8rem; 
            font-weight: 700;
            margin-bottom: 1rem;
            border-bottom: none; 
            color: #ffffff;
        }

        .hero-content p {
            font-size: 1.2rem;
            max-width: 600px;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        
        .cta-button {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            padding: 0.8rem 1.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            margin: 0 0.5rem;
        }
        .cta-button:hover {
            background: #0056b3;
            transform: translateY(-2px); 
        }
        .cta-button.secondary { 
            background: #ffffff;
            color: #007bff;
            border: 1px solid #007bff;
        }
        .cta-button.secondary:hover {
            background: #f0f8ff;
            color: #0056b3;
        }

        
        
        footer {
            background: #343a40; 
            color: #f8f9fa;
            text-align: center;
            padding: 2.5rem 0;
            margin-top: 3rem;
        }
    </style>
    </head>
<body>

    <header>
        <div class="container header-content">
            <h1>Eksplor Pariwisata Bali</h1>
            <nav>
                <a href="/">Home</a>
                <a href="/destinasi">Destinasi</a>
                <a href="/kuliner">Kuliner</a>
                <a href="/galeri">Galeri</a>
                <a href="/kontak">Kontak</a>
            </nav>
        </div>
    </header>

    <main class="content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Hak Cipta - Moch Ichwanul Muslimin Mayang (H071241027)</p>
    </footer>

</body>
</html>