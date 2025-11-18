@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="flex justify-between mb-4">
    <h1 class="text-2xl font-semibold">Products</h1>
    <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Product</a>
</div>

<table class="w-full border">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2 border">Name</th>
            <th class="p-2 border">Category</th>
            <th class="p-2 border">Price</th>
            <th class="p-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $p)
        <tr>
            <td class="p-2 border">{{ $p->name }}</td>
            <td class="p-2 border">{{ $p->category?->name ?? '-' }}</td>
            <td class="p-2 border">Rp {{ number_format($p->price, 2) }}</td>
            <td class="p-2 border space-x-2">
                <a href="{{ route('products.show', $p) }}" class="text-green-600">View</a>
                <a href="{{ route('products.edit', $p) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('products.destroy', $p) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button class="text-red-600" onclick="return confirm('Delete product?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">{{ $products->links() }}</div>
@endsection
