@extends('layouts.app')

@section('content')
<h2>Transfer / Update Stok</h2>
<div class="card">
    <div class="card-body">
        <form action="{{ route('stocks.processTransfer') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Pilih Gudang</label>
                <select name="warehouse_id" class="form-control" required>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Pilih Produk</label>
                <select name="product_id" class="form-control" required>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah (Quantity)</label>
                <small class="text-muted d-block">Gunakan angka positif untuk menambah, negatif untuk mengurangi.</small>
                <input type="number" name="quantity" class="form-control" placeholder="Contoh: 10 atau -5" required>
            </div>

            <button type="submit" class="btn btn-primary">Proses Stok</button>
        </form>
    </div>
</div>
@endsection