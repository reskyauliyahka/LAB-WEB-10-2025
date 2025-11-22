@extends('layouts.master')

@section('title','Manajemen Stok Gudang')

@section('konten')
    <div class="row">
        <div class="col-12">
            @if (session('message'))
                <div class="alert alert-primary">{{ session('message') }}</div>
            @endif
             @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Form Filter Gudang --}}
            <div class="card mb-4">
                <div class="card-header">
                    Filter Stok Gudang
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stock.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-5">
                                <label for="warehouse_id" class="form-label">Pilih Gudang</label>
                                <select name="warehouse_id" id="warehouse_id" class="form-select">
                                    <option value="">Tampilkan Semua Gudang</option>
                                    @foreach ($warehouses as $warehouse)
                                        {{-- Tampilkan 'selected' jika ID-nya cocok --}}
                                        <option value="{{ $warehouse->id }}" {{ $warehouse->id == $selectedWarehouseId ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Daftar Stok --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Daftar Stok Produk per Gudang
                    <a href="{{ route('stock.transfer.form') }}" class="btn btn-success">
                        <i class="fas fa-exchange-alt"></i> Transfer Stok
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Gudang</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Jumlah Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stockData as $item)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $item->warehouse_name }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td><strong>{{ $item->quantity }}</strong> Pcs</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        @if($selectedWarehouseId)
                                            Tidak ada stok produk di gudang yang dipilih.
                                        @else
                                            Belum ada data stok sama sekali.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection