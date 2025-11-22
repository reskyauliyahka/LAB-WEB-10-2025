@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Edit Product</h1>

<form action="{{ route('products.update', $product) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block font-semibold">Name</label>
        <input 
            type="text" 
            name="name" 
            value="{{ old('name', $product->name) }}" 
            class="w-full border p-2 rounded" 
            required>
    </div>

    <div>
        <label class="block font-semibold">Price</label>
        <input 
            type="number" 
            name="price" 
            value="{{ old('price', $product->price) }}" 
            step="0.01"
            class="w-full border p-2 rounded" 
            required>
    </div>

    <div>
        <label class="block font-semibold">Category</label>
        <select name="category_id" class="w-full border p-2 rounded">
            <option value="">-- None --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-semibold">Weight (kg)</label>
        <input 
            type="number" 
            name="weight" 
            step="0.01"
            value="{{ old('weight', $product->detail->weight ?? '') }}" 
            class="w-full border p-2 rounded" 
            required>
    </div>

    <div>
        <label class="block font-semibold">Size</label>
        <input 
            type="text" 
            name="size" 
            value="{{ old('size', $product->detail->size ?? '') }}" 
            class="w-full border p-2 rounded">
    </div>

    <div>
        <label class="block font-semibold">Description</label>
        <textarea 
            name="description" 
            class="w-full border p-2 rounded"
            rows="4">{{ old('description', $product->detail->description ?? '') }}</textarea>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
</form>
@endsection
