@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Manajemen Stok Gudang</h2>
    <a href="{{ route('stocks.transfer') }}" class="btn btn-primary">Transfer / Update Stok</a>
</div>

<form method="GET" action="{{ route('stocks.index') }}" class="mb-4">
    <div class="input-group">
        <select name="warehouse_id" class="form-control">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ $selectedWarehouseId == $wh->id ? 'selected' : '' }}>
                    {{ $wh->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Gudang</th>
            <th>Stok (Quantity)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            @if($product->warehouses->count() > 0)
                @foreach($product->warehouses as $warehouse)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $warehouse->name }}</td>
                    <td>{{ $warehouse->pivot->quantity }}</td> 
                </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $product->name }}</td>
                    <td colspan="2" class="text-muted">Belum ada stok di gudang (sesuai filter)</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@endsection