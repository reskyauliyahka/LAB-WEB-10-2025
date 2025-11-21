@extends('layouts.master')

@section('title','Transfer Stok')

@section('konten')
    <div class="row">
        <div class="col-md-8">
            {{-- Tampilkan error validasi dari controller --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            {{-- Tampilkan error validasi Laravel (jika ada) --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    Formulir Transfer Stok
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.transfer.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="warehouse_id" class="form-label">Pilih Gudang</label>
                            <select name="warehouse_id" id="warehouse_id" class="form-select" required>
                                <option value="">Pilih Gudang...</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Pilih Produk</label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="">Pilih Produk...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah Kuantitas</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required>
                            <div class="form-text">
                                Masukkan nilai positif (e.g., <b class="text-success">10</b>) untuk <b>menambah stok</b>.
                                <br>
                                Masukkan nilai negatif (e.g., <b class="text-danger">-5</b>) untuk <b>mengurangi stok</b>.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Proses Transfer</button>
                        <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
