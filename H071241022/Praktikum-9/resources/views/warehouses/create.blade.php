@extends('layouts.app')
@section('title', 'Add Warehouse')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Add Warehouse</h1>

<form action="{{ route('warehouses.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block font-semibold">Warehouse Name</label>
        <input 
            type="text" 
            name="name" 
            value="{{ old('name') }}" 
            class="w-full border p-2 rounded" 
            placeholder="Enter warehouse name" 
            required>
        @error('name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-semibold">Location</label>
        <input 
            type="text" 
            name="location" 
            value="{{ old('location') }}" 
            class="w-full border p-2 rounded" 
            placeholder="Enter location (optional)">
        @error('location')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex gap-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        <a href="{{ route('warehouses.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
    </div>
</form>
@endsection
