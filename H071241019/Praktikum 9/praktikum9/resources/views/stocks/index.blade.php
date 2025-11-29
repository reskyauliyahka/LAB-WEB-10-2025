@extends('layouts.app')
@section('title','Stock Management')

@section('content')

<div class="flex justify-between items-center mb-6">
  <h1 class="text-3xl font-semibold">Stock Management</h1>

  <a href="{{ route('stocks.create') }}"
     class="bg-linear-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
     + Stock Adjustment
  </a>
</div>

{{-- Warehouse Filter --}}
<div class="bg-white p-4 rounded-lg shadow-sm mb-6">
    <form method="GET" action="{{ route('stocks.index') }}" class="flex gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Warehouse</label>
            <select name="warehouse_id" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Warehouses</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ $selectedWarehouse == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Filter
        </button>
        @if($selectedWarehouse)
            <a href="{{ route('stocks.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 border rounded-lg">
                Clear
            </a>
        @endif
    </form>
</div>

{{-- Warehouse Summary --}}
@if(isset($warehouseTotals) && $warehouseTotals->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @foreach($warehouseTotals as $warehouse)
    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
        <h3 class="font-semibold text-gray-700">{{ $warehouse->name }}</h3>
        <p class="text-2xl font-bold text-blue-600">{{ $warehouse->total_quantity }} items</p>
        <p class="text-sm text-gray-500">Total Stock</p>
    </div>
    @endforeach
</div>
@endif

<div class="overflow-hidden shadow-xl rounded-xl">
  <table class="min-w-full bg-white">
    <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
      <tr>
        <th class="px-6 py-4 text-left">Product</th>
        <th class="px-6 py-4 text-left">Category</th>
        <th class="px-6 py-4 text-left">Warehouse</th>
        <th class="px-6 py-4 text-center">Stock Quantity</th>
        <th class="px-6 py-4 text-right">Price</th>
      </tr>
    </thead>

    <tbody class="text-gray-700">
      @forelse($stocks as $stock)
      <tr class="border-t hover:bg-gray-100">
        <td class="px-6 py-3">{{ $stock->product_name }}</td>
        <td class="px-6 py-3">{{ $stock->category_name ?? '-' }}</td>
        <td class="px-6 py-3">{{ $stock->warehouse_name }}</td>
        <td class="px-6 py-3 text-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                {{ $stock->quantity > 10 ? 'bg-green-100 text-green-800' : 
                   ($stock->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                {{ $stock->quantity }} pcs
            </span>
        </td>
        <td class="px-6 py-3 text-right">Rp {{ number_format($stock->price, 0, ',', '.') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="text-center py-5 text-gray-500">
            <div class="flex flex-col items-center">
                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"/>
                </svg>
                <p class="text-lg">No stock data found</p>
                <p class="text-sm mt-1">Try selecting a different warehouse or add some stock</p>
            </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection