@extends('layouts.master')

@section('title','Daftar Gudang')

@section('konten')
    <a href="/warehouses/create" class="btn btn-primary mb-3">Tambah Gudang</a>
    @if (session('message'))
        <div class="alert alert-primary">{{ session('message') }}</div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between  align-items-center">
            Daftar Gudang (Warehouse)
            <div class="d-flex gap-2">
                @if (Request()->keyword != '')
                    <a href="/warehouses" class="btn btn-info">Reset</a>
                @endif
                <form class="input-group" style="width: 350px">
                    <input type="text" class="form-control" value="{{ Request()->keyword }}" name="keyword"
                        placeholder="Cari nama atau lokasi gudang">
                    <button class="btn btn-success" type="submit">Cari Data</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Gudang</th>
                        <th scope="col">Lokasi</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warehouses as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->location }}</td>
                            <td>
                                <a href="/warehouses/{{ $item->id }}/edit" class="btn btn-warning">Edit</a>
                                {{-- 
                                    INI PERUBAHANNYA: 
                                    data-bs-toggle -> data-toggle
                                    data-bs-target -> data-target
                                --}}
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#hapus{{ $item->id }}">Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Data Gudang Tidak Ditemukan!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Hapus --}}
    @foreach ($warehouses as $item)
        {{-- 
            INI PERUBAHANNYA: 
            data-bs-backdrop -> data-backdrop
            data-bs-keyboard -> data-keyboard
        --}}
        <div class="modal fade" id="hapus{{ $item->id }}" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="/warehouses/{{ $item->id }}" method="POST" class="modal-content">
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
                        Apakah anda yakin ingin menghapus gudang {{ $item->name }}??
                        <br><small class="text-danger">Aksi ini juga akan menghapus semua data stok produk di gudang ini.</small>
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