@extends('layouts.master')

@section('title', 'Tambah Gudang')

@section('konten')
    <div class="card">
        <div class="card-header">Tambah Data Gudang</div>
        <div class="card-body">
            <form action="/warehouses" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Gudang </label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <textarea class="form-control" name="location" id="floatingTextarea2" style="height: 100px">{{ old('location') }}</textarea>
                    <label for="floatingTextarea2">Lokasi Gudang (Opsional)</label>
                    @error('location')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Tambah Data</button>
            </form>
        </div>
    </div>
@endsection
