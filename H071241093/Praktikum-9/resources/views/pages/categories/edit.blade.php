@extends('layouts.master')

@section('konten')
    <h1>Edit Kategori</h1>
    <hr>
    <div class="card">
        <div class="card-header">Edit Data Kategori</div>
        <div class="card-body">
            {{-- Ganti $kategori -> $category --}}
            {{-- Ganti action /kategori -> /categories dan $kategori->id_kategori -> $category->id --}}
            <form action="/categories/{{$category->id}}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Kategori </label>
                    {{-- Ganti name="nama_kategori" -> "name" dan $kategori->nama_kategori -> $category->name --}}
                    <input type="text" class="form-control" name="name" value="{{ $category->name }}">
                    @error('name')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <label class="form-label">Deskripsi</label>
                <div class="form-floating">
                    {{-- Ganti name="deskripsi" -> "description" dan $kategori->deskripsi -> $category->description --}}
                    <textarea class="form-control" name="description" id="floatingTextarea2" style="height: 100px">{{ $category->description }}</textarea>
                    <label for="floatingTextarea2">Deskripsi Kategori</label>
                    @error('description')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update Data</button>
            </form>
        </div>
    </div>
@endsection