@extends('layouts.app')

@section('title', $fish->name)

@section('content')
<div class="card-glass p-4">
    <div class="row g-3">
        <div class="col-md-4 text-center">
            <img src="{{ asset('images/fish_realistic_1.png') }}" class="img-fluid" alt="{{ $fish->name }}">
        </div>
        <div class="col-md-8">
            <h2>{{ $fish->name }}</h2>
            <div class="muted mb-2">Rarity: <span class="rarity">{{ $fish->rarity }}</span></div>
            <p class="muted">Berat: {{ $fish->base_weight_min }} - {{ $fish->base_weight_max }} kg</p>
            <p class="muted">Harga: Rp {{ number_format($fish->sell_price_per_kg,0,',','.') }}/kg</p>
            <p>{{ $fish->description }}</p>

            <div class="mt-3">
                <a href="{{ route('fishes.edit', $fish->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
