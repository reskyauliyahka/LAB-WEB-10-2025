@extends('layouts.app')
@section('title', 'Transfer Stock')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Transfer Stock</h1>
<form action="{{ route('stocks.transfer') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block font-semibold">Warehouse</label>
        <select name="warehouse_id" class="w-full border p-2 rounded" required>
            @foreach($warehouses as $w)
                <option value="{{ $w->id }}">{{ $w->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block font-semibold">Product</label>
        <select name="product_id" class="w-full border p-2 rounded" required>
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block font-semibold">Change in Quantity (+ add / - remove)</label>
        <input type="number" name="quantity" class="w-full border p-2 rounded" required>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Apply Transfer</button>
</form>
@endsection
