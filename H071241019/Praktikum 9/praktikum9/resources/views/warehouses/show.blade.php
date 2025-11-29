@extends('layouts.app')
@section('title','Warehouse Details')

@section('content')

<div class="max-w-xl mx-auto bg-white p-6 shadow-xl rounded-xl">

  <h1 class="text-3xl font-semibold mb-3">{{ $warehouse->name }}</h1>

  <p class="text-gray-600 mb-6">
    Location: {{ $warehouse->location ?? 'No location available.' }}
  </p>

  <div class="flex gap-3">
    <a href="{{ route('warehouses.edit',$warehouse) }}"
       class="bg-linear-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
       Edit
    </a>

    <a href="{{ route('warehouses.index') }}"
       class="px-4 py-2 rounded-lg border border-gray-400 hover:bg-gray-100">
       Back
    </a>
  </div>

</div>

@endsection
