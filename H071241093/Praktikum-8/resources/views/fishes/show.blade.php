@extends('layouts.app')

@section('title', 'Detail Ikan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Detail Ikan: <span class="text-primary">{{ $fish->name }}</span></h1>
    <a href="{{ route('fishes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Informasi Lengkap</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered table-striped-columns">
                    <tbody>
                        <tr>
                            <th style="width: 30%;">ID</th>
                            <td>{{ $fish->id }}</td>
                        </tr>
                        <tr>
                            <th>Nama Ikan</th>
                            <td class="fw-bold">{{ $fish->name }}</td>
                        </tr>
                        <tr>
                            <th>Rarity</th>
                            <td><span class="badge bg-info fs-6">{{ $fish->rarity }}</span></td>
                        </tr>
                        <tr>
                            <th>Range Berat</th>
                            <!-- Menampilkan data mentah + teks manual -->
                            <td>{{ $fish->base_weight_min }} kg - {{ $fish->base_weight_max }} kg</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <!-- Menampilkan data mentah + teks manual -->
                            <td>{{ $fish->getFormattedPriceAttribute() }} Coins/kg</td>
                        </tr>
                        <tr>
                            <th>Peluang Tangkap</th>
                            <!-- Menampilkan data mentah + teks manual -->
                            <td>{{ $fish->catch_probability }}%</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $fish->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Dibuat</th>
                            <td>{{ $fish->created_at->format('d M Y, H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Diupdate</th>
                            <td>{{ $fish->updated_at->format('d M Y, H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div class="alert alert-info text-center">
                    <img src="{{asset('images/kraken.jpg')}}" alt="kraken" class=" rounded-2xl mb-3" style="width: 100%;">
                    <h4 class="mt-2">{{ $fish->name }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-light">
        <div class="d-flex gap-2">
            <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form action="{{ route('fishes.destroy', $fish) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah kamu yakin ingin menghapus ikan {{ $fish->name }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

