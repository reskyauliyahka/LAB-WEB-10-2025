@extends('layouts.app')

@section('title', 'Edit Ikan')

@section('content')
<h2>Edit Ikan</h2>

<form action="{{ route('fishes.update', $fish->id) }}" method="POST" class="mt-3">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" class="form-control" name="name" value="{{ $fish->name }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Rarity</label>
        <select class="form-select" name="rarity">
            <option {{ $fish->rarity == 'Common' ? 'selected' : '' }}>Common</option>
            <option {{ $fish->rarity == 'Rare' ? 'selected' : '' }}>Rare</option>
            <option {{ $fish->rarity == 'Epic' ? 'selected' : '' }}>Epic</option>
            <option {{ $fish->rarity == 'Legendary' ? 'selected' : '' }}>Legendary</option>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Berat minimum (kg)</label>
            <input type="text" class="form-control" name="base_weight_min" value="{{ $fish->base_weight_min }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Berat maksimum (kg)</label>
            <input type="text" class="form-control" name="base_weight_max" value="{{ $fish->base_weight_max }}">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Harga jual per kg</label>
        <input type="text" class="form-control" name="sell_price_per_kg" value="{{ $fish->sell_price_per_kg }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Probability (%)</label>
        <input type="text" class="form-control" name="catch_probability" value="{{ $fish->catch_probability }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description">{{ $fish->description }}</textarea>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
