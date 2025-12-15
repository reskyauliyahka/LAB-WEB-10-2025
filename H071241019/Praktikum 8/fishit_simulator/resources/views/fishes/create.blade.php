@extends('layouts.app')

@section('title', 'Tambah Ikan')

@section('content')
<h2 class="mb-3">Tambah Ikan Baru</h2>

<form action="{{ route('fishes.store') }}" method="POST">
    @csrf

    <div class="card-glass mb-3">
        <label>Nama Ikan</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="card-glass mb-3">
        <label>Rarity</label>
        <select name="rarity" class="form-control" required>
            <option>Common</option>
            <option>Uncommon</option>
            <option>Rare</option>
            <option>Epic</option>
            <option>Legendary</option>
        </select>
    </div>

    <div class="card-glass mb-3">
        <label>Berat Minimum</label>
        <input type="number" step="0.1" name="base_weight_min" class="form-control" required>

        <label class="mt-2">Berat Maksimum</label>
        <input type="number" step="0.1" name="base_weight_max" class="form-control" required>
    </div>

    <div class="card-glass mb-3">
        <label>Harga Jual per kg (Rp)</label>
        <input type="number" name="sell_price_per_kg" class="form-control" required>

        <label class="mt-2">Peluang Tertangkap (%)</label>
        <input type="number" step="0.01" name="catch_probability" class="form-control" required>
    </div>

    <div class="card-glass mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>
@endsection
