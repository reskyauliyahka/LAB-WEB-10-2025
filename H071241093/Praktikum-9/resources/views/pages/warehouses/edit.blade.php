@extends('layouts.master')

@section('title', 'Edit Gudang')

@section('konten')
    <div class="card">
        <div class="card-header">Edit Data Gudang</div>
        <div class="card-body">
            <form action="/warehouses/{{ $warehouse->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Gudang </label>
                    <input type="text" class="form-control" name="name" value="{{ $warehouse->name }}">
                    @error('name')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <textarea class="form-control" name="location" id="floatingTextarea2" style="height: 100px">{{ $warehouse->location }}</textarea>
                    <label for="floatingTextarea2">Lokasi Gudang (Opsional)</label>
                    @error('location')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update Data</button>
            </form>
        </div>
    </div>
@endsection
