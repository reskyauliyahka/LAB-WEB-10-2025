@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Add Product</h1>
<form action="{{ route('products.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block font-semibold">Name</label>
        <input type="text" name="name" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block font-semibold">Price</label>
        <input type="number" name="price" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block font-semibold">Category</label>
        <select name="category_id" class="w-full border p-2 rounded">
            <option value="">-- None --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block font-semibold">Weight (kg)</label>
        <input type="number" name="weight" step="0.01" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block font-semibold">Size</label>
        <input type="text" name="size" class="w-full border p-2 rounded">
    </div>
    <div>
        <label class="block font-semibold">Description</label>
        <textarea name="description" class="w-full border p-2 rounded"></textarea>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
</form>
@endsection
