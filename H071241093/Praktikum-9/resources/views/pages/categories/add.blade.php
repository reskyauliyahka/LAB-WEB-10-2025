@extends('layouts.master')

@section('title','Tambah Kategori')

@section('konten')
    <h1>Tambah Daftar Kategori</h1>
    <hr>
    <div class="card">
        <div class="card-header">Tambah Data Kategori</div>
        <div class="card-body">
            {{-- Ganti action /kategori -> /categories --}}
            <form action="/categories" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Kategori </label>
                    {{-- Ganti name="nama_kategori" -> "name" --}}
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <label class="form-label">Deskripsi</label>
                <div class="form-floating">
                    {{-- Ganti name="deskripsi" -> "description" --}}
                    <textarea class="form-control" name="description" id="floatingTextarea2" style="height: 100px">{{ old('description') }}</textarea>
                    <label for="floatingTextarea2">Deskripsi Kategori</label>
                    @error('description')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Tambah Data</button>
            </form>
        </div>
    </div>
@endsection