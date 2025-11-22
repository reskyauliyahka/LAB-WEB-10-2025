@extends('layouts.master')

@section('title', 'Tambah Data Produk')

@section('konten')
    <div class="card">
        <div class="card-header">Tambah Data Produk</div>
        <div class="card-body"> 

            <form action="/products" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Harga Produk </label>
                            <input type="number" class="form-control" name="price" value="{{ old('price') }}">
                            @error('price')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Berat (dalam Kg, contoh: 1.5)</label>
                            <input type="text" class="form-control" name="weight" value="{{ old('weight') }}">
                            @error('weight')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Ukuran (contoh: 15 inch)</label>
                            <input type="text" class="form-control" name="size" value="{{ old('size') }}">
                            @error('size')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">Kategori </label>
                            <select class="form-select" aria-label="Default select example" name="category_id">
                                <option value="">Pilih Disini</option>
                                @foreach ($data_kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                            <textarea class="form-control" name="description" id="floatingTextarea2" style="height: 100px">{{ old('description') }}</textarea>
                            <label for="floatingTextarea2">Deskripsi Produk</label>
                            @error('description')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
