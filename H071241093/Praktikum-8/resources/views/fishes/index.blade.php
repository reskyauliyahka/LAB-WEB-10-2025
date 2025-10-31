@extends('layouts.app')

@section('title', 'Daftar Ikan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Daftar Ikan Fish It</h1>
    <a href="{{ route('fishes.create') }}" class="btn btn-primary" >
        <i class="bi bi-plus-lg" ></i> Tambah Ikan Baru
    </a>
</div>

<!-- Filter Rarity -->
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('fishes.index') }}" class="row g-3 align-items-center">
            <div class="col-md-4">
                <label for="rarity" class="form-label">Filter berdasarkan Rarity:</label>
            </div>
            <div class="col-md-5">
                <select name="rarity" id="rarity" class="form-select">
                    <option value="">Semua Rarity</option>
                    @foreach($rarities as $rarity)
                        <option value="{{ $rarity }}" {{ $rarityFilter == $rarity ? 'selected' : '' }}>
                            {{ $rarity }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Ikan -->
<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Rarity</th>
                    <th>Berat (Min-Max)</th>
                    <th>Harga Jual (per kg)</th>
                    <th>Peluang Tangkap</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fishes as $fish)
                <tr>
                    <td>{{ $fish->id }}</td>
                    <td class="fw-bold">{{ $fish->name }}</td>
                    <td><span class="badge bg-info">{{ $fish->rarity }}</span></td>
                    <td>{{ $fish->base_weight_min }} kg - {{ $fish->base_weight_max }} kg</td>
                    <td>{{ $fish->getFormattedPriceAttribute() }} Duit/kg</td>
                    <td>{{ $fish->catch_probability }}%</td>
                    <td>
                        <a href="{{ route('fishes.show', $fish) }}" class="btn btn-sm btn-outline-success" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('fishes.destroy', $fish) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah kamu yakin ingin menghapus ikan {{ $fish->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Data ikan tidak ditemukan.
                        @if($rarityFilter)
                            (dengan filter rarity: {{ $rarityFilter }})
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if ($fishes->hasPages())
    <div class="card-footer">
        {{ $fishes->links() }}
    </div>
    @endif
</div>
@endsection

