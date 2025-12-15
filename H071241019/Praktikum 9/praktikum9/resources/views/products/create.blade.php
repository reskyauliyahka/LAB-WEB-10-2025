@extends('layouts.app')
@section('title','Create Product')

@section('content')

<div class="max-w-2xl mx-auto bg-white p-6 shadow-xl rounded-xl">

  <h1 class="text-2xl font-semibold mb-6">Add Product</h1>

  <form action="{{ route('products.store') }}" method="POST">
    @csrf

    {{-- Name --}}
    <label class="font-medium block">Product Name</label>
    <input type="text" name="name" value="{{ old('name') }}" required
           class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500">

    {{-- Category --}}
    <label class="font-medium block">Category</label>
    <select name="category_id"
            class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500" required>
      <option value="">-- Select Category --</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
      @endforeach
    </select>

    {{-- Warehouse --}}
    <label class="font-medium block">Warehouse</label>
    <select name="warehouse_id"
            class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500" required>
      <option value="">-- Select Warehouse --</option>
      @foreach($warehouses as $warehouse)
        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
          {{ $warehouse->name }}
        </option>
      @endforeach
    </select>

    {{-- Stock --}}
    <label class="font-medium block">Initial Stock</label>
    <input type="number" name="quantity" value="{{ old('quantity') }}" required
           class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500" min="0">

    {{-- Price --}}
    <label class="font-medium block">Price</label>
    <input type="number" name="price" value="{{ old('price') }}" required
           class="border border-gray-300 p-2 rounded-lg w-full mb-6 focus:ring-2 focus:ring-indigo-500" min="0">

    {{-- Product Details --}}
    <div class="mb-6 pt-6 border-t">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">Product Details</h3>
      
      {{-- Description --}}
      <label class="font-medium block">Description</label>
      <textarea name="description" 
                class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500"
                rows="3"
                placeholder="Deskripsi lengkap produk">{{ old('description') }}</textarea>

      {{-- Weight --}}
      <label class="font-medium block">Weight (kg) <span class="text-red-500">*</span></label>
      <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" required
             class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500"
             placeholder="Contoh: 1.50" min="0">

      {{-- Size --}}
      <label class="font-medium block">Size</label>
      <input type="text" name="size" value="{{ old('size') }}"
             class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-indigo-500"
             placeholder="Contoh: 15 inch">
    </div>

    <div class="flex gap-3">
      <button type="submit"
        class="bg-linear-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
        Save Product
      </button>

      <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
        Cancel
      </a>
    </div>

  </form>
</div>

@endsection