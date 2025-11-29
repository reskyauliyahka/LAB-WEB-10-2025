@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-semibold mb-4 text-gray-800">➕ Add New Category</h2>

<form action="{{ route('categories.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
    @csrf

    <div>
        <label class="font-medium">Category Name</label>
        <input type="text" name="name" class="border p-2 w-full rounded focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label class="font-medium">Description</label>
        <textarea name="description" class="border p-2 w-full rounded focus:ring-2 focus:ring-blue-500"></textarea>
    </div>

    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition">
        Save Category
    </button>
</form>

@endsection
