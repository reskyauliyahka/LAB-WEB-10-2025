@extends('layouts.app')
@section('title','Products')

@section('content')

<div class="flex justify-between items-center mb-6">
  <h1 class="text-3xl font-semibold">Products</h1>

  <a href="{{ route('products.create') }}"
     class="bg-linear-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
     + Add Product
  </a>
</div>

<div class="overflow-hidden shadow-xl rounded-xl">
  <table class="min-w-full bg-white">
    <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
      <tr>
        <th class="px-6 py-4 text-left">Name</th>
        <th class="px-6 py-4 text-left">Category</th>
        <th class="px-6 py-4 text-left">Warehouse</th>
        <th class="px-6 py-4 text-center">Stock</th>
        <th class="px-6 py-4 text-left">Price</th>
        <th class="px-6 py-4 text-center w-40">Actions</th>
      </tr>
    </thead>

    <tbody class="text-gray-700">
      @forelse($products as $product)
      <tr class="border-t hover:bg-gray-100">
        <td class="px-6 py-3">{{ $product->name }}</td>
        <td class="px-6 py-3">{{ $product->category->name }}</td>
        <td class="px-6 py-3">
            @if($product->warehouses->count() > 0)
                {{ $product->warehouses->first()->name }} ({{ $product->warehouses->first()->pivot->quantity }})
            @else
                -
            @endif
        </td>
        <td class="px-6 py-3 text-center">
            {{ $product->warehouses->sum('pivot.quantity') }}
        </td>
        <td class="px-6 py-3">Rp {{ number_format($product->price,0,',','.') }}</td>

        <td class="px-6 py-3 text-center space-x-3">
          <a href="{{ route('products.show',$product) }}" class="text-green-600 font-semibold hover:underline">View</a>
          <a href="{{ route('products.edit',$product) }}" class="text-blue-600 font-semibold hover:underline">Edit</a>

          <form action="{{ route('products.destroy',$product) }}" method="POST" class="inline"
                onsubmit="return confirm('Delete this product?')">
            @csrf @method('DELETE')
            <button class="text-red-600 font-semibold hover:underline">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center py-5 text-gray-500">No products found.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection