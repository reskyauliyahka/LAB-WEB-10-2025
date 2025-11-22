@extends('layouts.app')
@section('title', 'Categories')

@section('content')
<div class="flex justify-between mb-4">
    <h1 class="text-2xl font-semibold">Categories</h1>
    <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Category</a>
</div>

<table class="w-full border">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2 border">#</th>
            <th class="p-2 border">Name</th>
            <th class="p-2 border">Description</th>
            <th class="p-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $c)
        <tr>
            <td class="p-2 border">{{ $loop->iteration }}</td>
            <td class="p-2 border">{{ $c->name }}</td>
            <td class="p-2 border">{{ $c->description }}</td>
            <td class="p-2 border space-x-2">
                <a href="{{ route('categories.edit', $c) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('categories.destroy', $c) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button class="text-red-600" onclick="return confirm('Delete this category?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">{{ $categories->links() }}</div>
@endsection
