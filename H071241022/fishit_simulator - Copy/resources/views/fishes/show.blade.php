@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h4>Detail Ikan: {{ $fish->name }}</h4>
    </div>
    <div class="card-body">
        <table class="table">
            <tr><th>Nama</th><td>{{ $fish->name }}</td></tr>
            <tr><th>Rarity</th><td>{{ $fish->rarity }}</td></tr>
            <tr><th>Berat Minimum</th><td>{{ $fish->base_weight_min }} kg</td></tr>
            <tr><th>Berat Maksimum</th><td>{{ $fish->base_weight_max }} kg</td></tr>
            <tr><th>Harga per Kg</th><td>{{ $fish->sell_price_per_kg }} Coins</td></tr>
            <tr><th>Peluang Tertangkap</th><td>{{ $fish->catch_probability }}%</td></tr>
            <tr><th>Deskripsi</th><td>{{ $fish->description ?? '-' }}</td></tr>
            <tr><th>Dibuat</th><td>{{ $fish->created_at->format('d M Y, H:i') }}</td></tr>
            <tr><th>Diperbarui</th><td>{{ $fish->updated_at->format('d M Y, H:i') }}</td></tr>
        </table>

        <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('fishes.destroy', $fish) }}" method="POST" style="display:inline-block">
            @csrf @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('Yakin hapus ikan ini?')">Hapus</button>
        </form>
        <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection

