@extends('layouts.app')

@section('content')
<h2>Tambah Produk Baru</h2>
<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Data Umum</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label> <select name="category_id" class="form-control">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Detail Spesifikasi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Berat (kg)</label>
                        <input type="number" step="0.01" name="weight" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Ukuran (Size)</label>
                        <input type="text" name="size" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3">Simpan Produk</button>
</form>
@endsection