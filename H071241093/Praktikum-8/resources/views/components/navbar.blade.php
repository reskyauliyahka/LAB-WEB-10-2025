 <nav class="navbar navbar-expand-lg  shadow sticky-top" style="background-color: #00bfffbc;">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="{{ route('fishes.index') }}">
                <img src="{{asset('images/buntal.png')}}" alt="gambar hiu" style="width: 40px; height: auto;">
                FISH IT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fishes.index') ? 'active' : '' }}" href="{{ route('fishes.index') }}" style="color: white" >Daftar Ikan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fishes.create') ? 'active' : '' }}" href="{{ route('fishes.create') }}" style="color: rgba(255, 255, 255, 0.87)" >Tambah Ikan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>