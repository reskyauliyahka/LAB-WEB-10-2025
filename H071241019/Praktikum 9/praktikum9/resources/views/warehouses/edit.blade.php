@extends('layouts.app')
@section('title','Edit Warehouse')

@section('content')

<div class="max-w-xl mx-auto bg-white p-6 shadow-xl rounded-xl">

  <h1 class="text-2xl font-semibold mb-4">Edit Warehouse</h1>

  <form action="{{ route('warehouses.update',$warehouse) }}" method="POST">
    @csrf @method('PUT')

    <label class="font-medium block">Warehouse Name</label>
    <input type="text" name="name" value="{{ old('name', $warehouse->name) }}" required
           class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-indigo-500 mb-4">

    <label class="font-medium block">Location</label>
    <input type="text" name="location" value="{{ old('location', $warehouse->location) }}"
           class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-indigo-500 mb-4">

    <div class="flex gap-3">
      <button
        class="bg-linear-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
        Update
      </button>

      <a href="{{ route('warehouses.index') }}"
         class="px-4 py-2 rounded-lg border border-gray-400 hover:bg-gray-100">
        Cancel
      </a>
    </div>
  </form>
</div>

@endsection
