@extends('layouts.app')
@section('title', 'Product Details')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Product Details</h1>

<div class="space-y-4">
    <div>
        <label class="font-semibold">Name:</label>
        <span>{{ $product->name }}</span>
    </div>

    <div>
        <label class="font-semibold">Category:</label>
        <span>{{ $product->category?->name ?? '-' }}</span>
    </div>

    <div>
        <label class="font-semibold">Price:</label>
        <span>Rp {{ number_format($product->price, 2) }}</span>
    </div>

    @if($product->detail)
    <div>
        <label class="font-semibold">Description:</label>
        <p class="border p-2 rounded">{{ $product->detail->description }}</p>
    </div>

    <div>
        <label class="font-semibold">Weight:</label>
        <span>{{ $product->detail->weight }} kg</span>
    </div>

    <div>
        <label class="font-semibold">Size:</label>
        <span>{{ $product->detail->size }}</span>
    </div>
    @endif

    <div>
        <label class="font-semibold">Warehouses & Stock:</label>
        <table class="mt-2 w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Warehouse</th>
                    <th class="p-2 border">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @forelse($product->warehouses as $w)
                <tr>
                    <td class="p-2 border">{{ $w->name }}</td>
                    <td class="p-2 border text-center">{{ $w->pivot->quantity }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="p-2 border text-center text-gray-500">No stock data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">← Back to Products</a>
</div>
@endsection
