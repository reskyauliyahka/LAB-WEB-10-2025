@extends('layouts.app')

@section('title', 'Daftar Ikan')

@section('content')
<h2 class="mb-3">Daftar Ikan</h2>

@if($fishes->isEmpty())
    <p class="muted">Belum ada ikan di database.</p>
@else
    <div class="fish-grid">
        @foreach($fishes as $fish)
            <div class="card-glass">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <img src="/images/fish_realistic_1.png" class="fish-photo" alt="fish">

                    <div>
                        <h5 style="margin:0;">{{ $fish->name }}</h5>
                        <div class="rarity">{{ $fish->rarity }}</div>
                    </div>
                </div>

                <div class="muted">
                    Berat: {{ $fish->base_weight_min }} – {{ $fish->base_weight_max }} kg <br>
                    Harga jual: Rp {{ number_format($fish->sell_price_per_kg) }}/kg <br>
                    Peluang tertangkap: {{ $fish->catch_probability }}%
                </div>

                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('fishes.edit', $fish->id) }}" class="btn btn-outline-light btn-sm">Edit</a>

                    <form action="{{ route('fishes.destroy', $fish->id) }}" method="POST" onsubmit="return confirm('Yakin hapus ikan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
