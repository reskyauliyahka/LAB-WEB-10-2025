@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Gudang (Warehouse)</h2>
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary">Tambah Gudang</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th width="50">No</th>
                    <th>Nama Gudang</th>
                    <th>Lokasi</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warehouses as $warehouse)
                <tr>
                    <td>{{ $loop->iteration + ($warehouses->currentPage() - 1) * $warehouses->perPage() }}</td>
                    <td>{{ $warehouse->name }}</td>
                    <td>{{ $warehouse->location ?? '-' }}</td>
                    <td>
                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" onsubmit="return confirm('Hapus gudang ini? Data stok terkait akan hilang!');">
                            <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Data gudang belum tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $warehouses->links() }}
        </div>
    </div>
</div>
@endsection