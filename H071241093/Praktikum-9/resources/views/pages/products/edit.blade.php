@extends('layouts.master')

@section('title', 'Edit Data Produk')

@section('konten')
    <div class="card">
        <div class="card-header">Update Data Produk</div>
        <div class="card-body">
            {{-- Ganti $data -> $product --}}
            <form action="/products/{{ $product->id }}" method="POST">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            {{-- Ganti name="nama_produk" -> "name" --}}
                            {{-- Ganti $data->nama_produk -> $product->name --}}
                            <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                            @error('name')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Harga Produk </label>
                            {{-- Ganti name="harga_produk" -> "price" --}}
                            {{-- Ganti $data->harga -> $product->price --}}
                            <input type="number" class="form-control" name="price" value="{{ $product->price }}">
                            @error('price')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Berat (dalam Kg, contoh: 1.5)</label>
                            {{-- Input baru 'weight', ambil dari relasi productDetail --}}
                            {{-- Tambahkan 'old()' untuk menjaga data jika validasi gagal --}}
                            <input type="text" class="form-control" name="weight"
                                value="{{ old('weight', $product->productDetail->weight ?? '') }}">
                            @error('weight')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Ukuran (contoh: 15 inch)</label>
                            {{-- Input baru 'size', ambil dari relasi productDetail --}}
                            <input type="text" class="form-control" name="size"
                                value="{{ old('size', $product->productDetail->size ?? '') }}">
                            @error('size')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">Kategori </label>
                            {{-- Ganti name="kategori" -> "category_id" --}}
                            <select class="form-select" aria-label="Default select example" name="category_id">
                                <option value="">Pilih Disini</option>
                                @foreach ($kategori as $item)
                                    {{-- Ganti $item->id_kategori -> $item->id --}}
                                    {{-- Ganti $data->kategori_id -> $product->category_id --}}
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $product->category_id ? 'selected' : '' }}>
                                        {{ $item->name }} {{-- Ganti $item->nama_kategori -> $item->name --}}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label">Deskripsi</label>
                        <div class="form-floating">
                            {{-- Ganti name="deskripsi" -> "description" --}}
                            {{-- Ambil dari relasi productDetail --}}
                            <textarea class="form-control" name="description" id="floatingTextarea2" style="height: 100px">{{ old('description', $product->productDetail->description ?? '') }}</textarea>
                            <label for="floatingTextarea2">Deskripsi Produk</label>
                            @error('description')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
