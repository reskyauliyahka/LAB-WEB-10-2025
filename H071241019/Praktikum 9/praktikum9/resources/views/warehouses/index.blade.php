@extends('layouts.app')
@section('title','Warehouses')

@section('content')

<div class="flex justify-between items-center mb-6">
  <h1 class="text-3xl font-semibold">Warehouses</h1>

  <a href="{{ route('warehouses.create') }}"
     class="bg-linear-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
     + Add Warehouse
  </a>
</div>

<div class="overflow-hidden shadow-xl rounded-xl">
  <table class="min-w-full bg-white">
    <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
      <tr>
        <th class="px-6 py-4 text-left">Name</th>
        <th class="px-6 py-4 text-left">Location</th>
        <th class="px-6 py-4 text-center w-40">Actions</th>
      </tr>
    </thead>

    <tbody class="text-gray-700">
      @forelse($warehouses as $warehouse)
      <tr class="border-t hover:bg-gray-100">
        <td class="px-6 py-3">{{ $warehouse->name }}</td>
        <td class="px-6 py-3">{{ $warehouse->location }}</td>
        <td class="px-6 py-3 text-center space-x-3">

          <a href="{{ route('warehouses.show',$warehouse) }}"
             class="text-green-600 font-semibold hover:underline">
             View
          </a>

          <a href="{{ route('warehouses.edit',$warehouse) }}"
             class="text-blue-600 font-semibold hover:underline">
             Edit
          </a>

          <form action="{{ route('warehouses.destroy',$warehouse) }}" method="POST"
                class="inline" onsubmit="return confirm('Delete this warehouse?')">
            @csrf @method('DELETE')
            <button class="text-red-600 font-semibold hover:underline">Delete</button>
          </form>

        </td>
      </tr>
      @empty
      <tr>
        <td colspan="3" class="text-center py-5 text-gray-500">No warehouses found.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
