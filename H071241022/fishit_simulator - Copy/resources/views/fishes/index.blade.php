@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Daftar Ikan</h2>
    <a href="{{ route('fishes.create') }}" class="btn btn-success">+ Tambah Ikan</a>
</div>

<form method="GET" class="mb-3">
    <select name="rarity" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
        <option value="">-- Filter Rarity --</option>
        @foreach(['Common','Uncommon','Rare','Epic','Legendary','Mythic','Secret'] as $r)
            <option value="{{ $r }}" {{ $rarity == $r ? 'selected' : '' }}>{{ $r }}</option>
        @endforeach
    </select>
</form>

<table class="table table-bordered table-striped">
    <thead class="table-primary">
        <tr>
            <th>Nama</th>
            <th>Rarity</th>
            <th>Harga/kg</th>
            <th>Peluang (%)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($fishes as $fish)
        <tr>
            <td>{{ $fish->name }}</td>
            <td>{{ $fish->rarity }}</td>
            <td>{{ $fish->sell_price_per_kg }}</td>
            <td>{{ $fish->catch_probability }}</td>
            <td>
                <a href="{{ route('fishes.show', $fish) }}" class="btn btn-info btn-sm">Detail</a>
                <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('fishes.destroy', $fish) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus ikan ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $fishes->links() }}
@endsection
