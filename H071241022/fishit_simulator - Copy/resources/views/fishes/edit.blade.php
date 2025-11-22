@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-warning">
        <h4>Edit Data Ikan</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('fishes.update', $fish->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Ikan</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $fish->name) }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Rarity</label>
                <select name="rarity" class="form-select" required>
                    @foreach(['Common','Uncommon','Rare','Epic','Legendary','Mythic','Secret'] as $r)
                        <option value="{{ $r }}" {{ old('rarity', $fish->rarity) == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
                @error('rarity') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Berat Minimum (kg)</label>
                    <input type="number" name="base_weight_min" step="0.01" class="form-control" value="{{ old('base_weight_min', $fish->base_weight_min) }}" required>
                </div>
                <div class="col">
                    <label class="form-label">Berat Maksimum (kg)</label>
                    <input type="number" name="base_weight_max" step="0.01" class="form-control" value="{{ old('base_weight_max', $fish->base_weight_max) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga Jual per Kg</label>
                <input type="number" name="sell_price_per_kg" class="form-control" value="{{ old('sell_price_per_kg', $fish->sell_price_per_kg) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Peluang Tertangkap (%)</label>
                <input type="number" name="catch_probability" step="0.01" class="form-control" value="{{ old('catch_probability', $fish->catch_probability) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $fish->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
            <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
