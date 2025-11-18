@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h4>Tambah Ikan Baru</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('fishes.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Ikan</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Rarity</label>
                <select name="rarity" class="form-select" required>
                    <option value="">-- Pilih Rarity --</option>
                    @foreach(['Common','Uncommon','Rare','Epic','Legendary','Mythic','Secret'] as $r)
                        <option value="{{ $r }}" {{ old('rarity') == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
                @error('rarity') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Berat Minimum (kg)</label>
                    <input type="number" name="base_weight_min" step="0.01" class="form-control" value="{{ old('base_weight_min') }}" required>
                    @error('base_weight_min') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col">
                    <label class="form-label">Berat Maksimum (kg)</label>
                    <input type="number" name="base_weight_max" step="0.01" class="form-control" value="{{ old('base_weight_max') }}" required>
                    @error('base_weight_max') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga Jual per Kg (Coins)</label>
                <input type="number" name="sell_price_per_kg" class="form-control" value="{{ old('sell_price_per_kg') }}" required>
                @error('sell_price_per_kg') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Peluang Tertangkap (%)</label>
                <input type="number" name="catch_probability" step="0.01" class="form-control" value="{{ old('catch_probability') }}" required>
                @error('catch_probability') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi (Opsional)</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
