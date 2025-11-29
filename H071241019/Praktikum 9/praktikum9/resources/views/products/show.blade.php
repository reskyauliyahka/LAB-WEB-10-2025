@extends('layouts.app')
@section('title','Product Details')

@section('content')

<div class="bg-white p-6 rounded-xl shadow-xl max-w-4xl mx-auto">

  <h1 class="text-3xl font-semibold mb-6">{{ $product->name }}</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    {{-- Basic Info --}}
    <div class="space-y-4">
      <h3 class="text-lg font-semibold border-b pb-2">Basic Information</h3>
      
      <p class="text-gray-600"><strong>Category:</strong> {{ $product->category->name ?? '-' }}</p>
      <p class="text-gray-600"><strong>Price:</strong> Rp {{ number_format($product->price,0,',','.') }}</p>
    </div>

    {{-- Product Details --}}
    <div class="space-y-4">
      <h3 class="text-lg font-semibold border-b pb-2">Product Details</h3>
      
      <p class="text-gray-600"><strong>Description:</strong><br>
        {{ $product->detail->description ?? 'No description' }}
      </p>
      <p class="text-gray-600"><strong>Weight:</strong> {{ $product->detail->weight ?? '0' }} kg</p>
      <p class="text-gray-600"><strong>Size:</strong> {{ $product->detail->size ?? '-' }}</p>
    </div>
  </div>

  {{-- Stock Information --}}
  <div class="mb-6">
    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Stock Information</h3>
    
    @if($product->warehouses->count() > 0)
      <div class="space-y-2">
        @foreach($product->warehouses as $warehouse)
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
          <span class="font-medium">{{ $warehouse->name }}</span>
          <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
            {{ $warehouse->pivot->quantity }} pcs
          </span>
        </div>
        @endforeach
        
        {{-- Total Stock --}}
        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-200 mt-3">
          <span class="font-semibold text-green-800">TOTAL STOCK</span>
          <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold">
            {{ $product->warehouses->sum('pivot.quantity') }} pcs
          </span>
        </div>
      </div>
    @else
      <p class="text-gray-500 text-center py-4">No stock available in any warehouse</p>
    @endif
  </div>

  <div class="flex gap-3 pt-4 border-t">
    <a href="{{ route('products.edit',$product) }}"
       class="bg-linear-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
       Edit Product
    </a>

    <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-lg border border-gray-400 hover:bg-gray-100">
      Back to Products
    </a>
  </div>

</div>

@endsection