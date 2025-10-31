@extends('layouts.app')

@section('title', 'Tambah Ikan Baru')

@section('content')
<h1 class="h2 mb-4">Tambah Ikan Baru</h1>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('fishes.store') }}" method="POST">
            @csrf
            
            <div class="row g-3">
                <!-- Nama Ikan -->
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Ikan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Rarity -->
                <div class="col-md-6">
                    <label for="rarity" class="form-label">Rarity <span class="text-danger">*</span></label>
                    <select class="form-select @error('rarity') is-invalid @enderror" id="rarity" name="rarity" required>
                        <option value="" disabled selected>Pilih Rarity</option>
                        @foreach($rarities as $rarity)
                            <option value="{{ $rarity }}" {{ old('rarity') == $rarity ? 'selected' : '' }}>
                                {{ $rarity }}
                            </option>
                        @endforeach
                    </select>
                    @error('rarity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Berat Minimum -->
                <div class="col-md-6">
                    <label for="base_weight_min" class="form-label">Berat Minimum (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('base_weight_min') is-invalid @enderror" id="base_weight_min" name="base_weight_min" value="{{ old('base_weight_min') }}" placeholder="Contoh: 1.50" required>
                    @error('base_weight_min')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Berat Maksimum -->
                <div class="col-md-6">
                    <label for="base_weight_max" class="form-label">Berat Maksimum (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('base_weight_max') is-invalid @enderror" id="base_weight_max" name="base_weight_max" value="{{ old('base_weight_max') }}" placeholder="Contoh: 5.75" required>
                    @error('base_weight_max')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga Jual per kg -->
                <div class="col-md-6">
                    <label for="sell_price_per_kg" class="form-label">Harga Jual (Coins/kg) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('sell_price_per_kg') is-invalid @enderror" id="sell_price_per_kg" name="sell_price_per_kg" value="{{ old('sell_price_per_kg') }}" placeholder="Contoh: 150" required>
                    @error('sell_price_per_kg')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Peluang Tangkap -->
                <div class="col-md-6">
                    <label for="catch_probability" class="form-label">Peluang Tangkap (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('catch_probability') is-invalid @enderror" id="catch_probability" name="catch_probability" value="{{ old('catch_probability') }}" placeholder="Antara 0.01 - 100.00" required>
                    @error('catch_probability')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Ikan
                </button>
                <a href="{{ route('fishes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
