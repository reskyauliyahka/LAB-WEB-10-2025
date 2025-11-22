@extends('layouts.master')

@section('konten')
    {{-- Ganti $produk -> $product --}}
    <h1>Detail Produk: {{$product->name}}</h1>
    <hr>
    <div class="card">
        <div class="card-header">
            Detail Produk {{$product->name}}
        </div>
        <div class="card-body">
            <img src="https://placehold.co/600x400" class="img-fluid mb-3" alt="">
            
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 200px;">Nama Produk</th>
                        <td>{{$product->name}}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        {{-- Ambil dari relasi category --}}
                        <td>{{$product->category->name ?? 'Tanpa Kategori'}}</td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        {{-- Ambil dari relasi productDetail --}}
                        <td>{{$product->productDetail->weight ?? 'N/A'}} Kg</td>
                    </tr>
                    <tr>
                        <th>Ukuran</th>
                        {{-- Ambil dari relasi productDetail --}}
                        <td>{{$product->productDetail->size ?? 'N/A'}}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        {{-- Ambil dari relasi productDetail --}}
                        <td>{{$product->productDetail->description ?? 'Tidak ada deskripsi'}}</td>
                    </tr>
                </tbody>
            </table>
            
            {{-- Hapus STOK --}}
            <a href="/products" class="btn btn-primary">Kembali Ke Daftar Produk</a>
        </div>
    </div>
@endsection 