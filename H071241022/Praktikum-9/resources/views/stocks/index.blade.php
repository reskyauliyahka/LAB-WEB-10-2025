@extends('layouts.app')
@section('title', 'Stock Management')

@section('content')
<div class="flex justify-between mb-4">
    <h1 class="text-2xl font-semibold">Stock Overview</h1>
    <a href="{{ route('stocks.transferForm') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Transfer Stock</a>
</div>

<form method="GET" class="mb-4">
    <label>Filter by Warehouse:</label>
    <select name="warehouse_id" onchange="this.form.submit()" class="border p-2 rounded">
        <option value="">All</option>
        @foreach($warehouses as $w)
            <option value="{{ $w->id }}" {{ $warehouseId == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
        @endforeach
    </select>
</form>

<table class="w-full border">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2 border">Product</th>
            <th class="p-2 border">Warehouse</th>
            <th class="p-2 border">Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stocks as $s)
        <tr>
            <td class="p-2 border">{{ $s->product->name }}</td>
            <td class="p-2 border">{{ $s->warehouse->name }}</td>
            <td class="p-2 border text-center">{{ $s->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">{{ $stocks->links() }}</div>
@endsection
