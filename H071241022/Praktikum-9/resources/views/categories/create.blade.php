@extends('layouts.app')
@section('title', 'Add Category')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Add Category</h1>
<form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block font-semibold">Name</label>
        <input type="text" name="name" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block font-semibold">Description</label>
        <textarea name="description" class="w-full border p-2 rounded"></textarea>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
</form>
@endsection
