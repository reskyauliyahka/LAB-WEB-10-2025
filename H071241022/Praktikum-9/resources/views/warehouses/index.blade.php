@extends('layouts.app')
@section('title', 'Warehouses')

@section('content')
<div class="flex justify-between mb-4">
    <h1 class="text-2xl font-semibold">Warehouses</h1>
    <a href="{{ route('warehouses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Warehouse</a>
</div>

<table class="w-full border">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2 border">#</th>
            <th class="p-2 border">Name</th>
            <th class="p-2 border">Location</th>
            <th class="p-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($warehouses as $w)
        <tr>
            <td class="p-2 border">{{ $loop->iteration }}</td>
            <td class="p-2 border">{{ $w->name }}</td>
            <td class="p-2 border">{{ $w->location }}</td>
            <td class="p-2 border space-x-2">
                <a href="{{ route('warehouses.edit', $w) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('warehouses.destroy', $w) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button class="text-red-600" onclick="return confirm('Delete warehouse?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">{{ $warehouses->links() }}</div>
@endsection
