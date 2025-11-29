@extends('layouts.app')
@section('title','Edit Product')

@section('content')

<div class="max-w-2xl mx-auto bg-white p-6 shadow-xl rounded-xl">

  <h1 class="text-2xl font-semibold mb-6">Edit Product</h1>

  <form action="{{ route('products.update', $product) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Name --}}
    <label class="font-medium block">Product Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
           class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500">

    {{-- Category --}}
    <label class="font-medium block">Category</label>
    <select name="category_id"
            class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500" required>
      <option value="">-- Select Category --</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
      @endforeach
    </select>

    {{-- Price --}}
    <label class="font-medium block">Price</label>
    <input type="number" name="price" value="{{ old('price', $product->price) }}" required
           class="border border-gray-300 p-2 rounded-lg w-full mb-6 focus:ring-2 focus:ring-indigo-500" min="0">

    {{-- Product Details --}}
    <div class="mb-6 pt-6 border-t">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">Product Details</h3>
      
      {{-- Description --}}
      <label class="font-medium block">Description</label>
      <textarea name="description" 
                class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500"
                rows="3"
                placeholder="Deskripsi lengkap produk">{{ old('description', $product->detail->description ?? '') }}</textarea>

      {{-- Weight --}}
      <label class="font-medium block">Weight (kg) <span class="text-red-500">*</span></label>
      <input type="number" step="0.01" name="weight" value="{{ old('weight', $product->detail->weight ?? '') }}" required
             class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500"
             placeholder="Contoh: 1.50" min="0">

      {{-- Size --}}
      <label class="font-medium block">Size</label>
      <input type="text" name="size" value="{{ old('size', $product->detail->size ?? '') }}"
             class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500"
             placeholder="Contoh: 15 inch">
    </div>

    <div class="flex gap-3">
      <button type="submit"
        class="bg-linear-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
        Update Product
      </button>

      <a href="{{ route('products.show', $product) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
        Cancel
      </a>
    </div>

  </form>

  {{-- Stock Management Section --}}
  <div class="mt-8 pt-6 border-t">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Stock Management</h3>
    
    @if($product->warehouses->count() > 0)
      <div class="space-y-3">
        @foreach($product->warehouses as $warehouse)
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
          <div>
            <span class="font-medium">{{ $warehouse->name }}</span>
            <span class="text-sm text-gray-500 ml-2">({{ $warehouse->location }})</span>
          </div>
          <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
            {{ $warehouse->pivot->quantity }} pcs
          </span>
        </div>
        @endforeach
      </div>
    @else
      <p class="text-gray-500 text-center py-4">No stock available</p>
    @endif

    <div class="mt-4">
      <a href="#" class="text-blue-600 hover:underline text-sm">Manage Stock Transfer →</a>
    </div>
  </div>

</div>

@endsection