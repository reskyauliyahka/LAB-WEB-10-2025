@extends('layouts.master')

@section('title','Daftar Produk')

@section('konten')
    <a href="/products/create" class="btn btn-sm btn-primary mb-3">Tambah Data</a>
    <div class="alert alert-primary" role="alert">
        <b>Nama Toko : </b> {{ $data_toko['nama_toko'] }} <br>
        <b>Alamat : </b> {{ $data_toko['alamat'] }} <br>
        <b>Tipe Toko : </b> {{ $data_toko['type'] }}
    </div> 
    @if (session('message'))
        <div class="alert alert-primary">{{ session('message') }}</div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between  align-items-center">
            Daftar Produk
            <div class="d-flex gap-2">
                @if (Request()->keyword != '')
                    <a href="/products" class="btn btn-info">Reset</a>
                @endif
                <form class="input-group" style="width: 350px">
                    <input type="text" class="form-control" value="{{ Request()->keyword }}" name="keyword"
                        placeholder="Cari data produk">
                    <button class="btn btn-success" type="submit">Cari Data</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Produk</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_produk as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->category->name ?? 'Tanpa Kategori' }}</td>
                            <td>
                                {{-- 
                                    INI PERUBAHANNYA: 
                                    data-bs-toggle -> data-toggle
                                    data-bs-target -> data-target
                                --}}
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#hapus{{ $item->id }}">Hapus
                                </button>
                                <a href="/products/{{ $item->id }}/edit" class="btn btn-warning">Edit</a>
                                <a href="/products/{{ $item->id }}" class="btn btn-info">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Data yang anda cari tidak ada!!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Hapus --}}
    @foreach ($data_produk as $item)
        {{-- 
            INI PERUBAHANNYA: 
            data-bs-backdrop -> data-backdrop
            data-bs-keyboard -> data-keyboard
        --}}
        <div class="modal fade" id="hapus{{ $item->id }}" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="/products/{{ $item->id }}" method="POST" class="modal-content">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi</h1>
                        {{-- 
                            INI PERUBAHANNYA: 
                            data-bs-dismiss -> data-dismiss
                        --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus produk {{ $item->name }}??
                    </div>
                    <div class="modal-footer">
                        {{-- 
                            INI PERUBAHANNYA: 
                            data-bs-dismiss -> data-dismiss
                        --}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Data</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection