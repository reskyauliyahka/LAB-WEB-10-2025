@extends('layouts.app')
@section('title','Stock Adjustment')

@section('content')

<div class="max-w-2xl mx-auto bg-white p-6 shadow-xl rounded-xl">

  <h1 class="text-2xl font-semibold mb-6">Stock Adjustment</h1>

  <form action="{{ route('stocks.store') }}" method="POST" id="stockForm">
    @csrf

    {{-- Warehouse --}}
    <label class="block font-medium mb-1">Warehouse <span class="text-red-500">*</span></label>
    <select name="warehouse_id" id="warehouse_id" required
            class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-blue-500">
      <option value="">-- Select Warehouse --</option>
      @foreach($warehouses as $warehouse)
        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
      @endforeach
    </select>

    {{-- Product --}}
    <label class="block font-medium mb-1">Product <span class="text-red-500">*</span></label>
    <select name="product_id" id="product_id" required
            class="border border-gray-300 p-2 rounded-lg w-full mb-4 focus:ring-2 focus:ring-blue-500">
      <option value="">-- Select Product --</option>
      @foreach($products as $product)
        <option value="{{ $product->id }}">
          {{ $product->name }} (Rp {{ number_format($product->price, 0, ',', '.') }})
        </option>
      @endforeach
    </select>

    {{-- Current Stock Display --}}
    <div class="mb-4 p-3 bg-gray-50 rounded-lg hidden" id="currentStockContainer">
        <p class="text-sm text-gray-600">Current stock in selected warehouse: 
            <span id="currentStock" class="font-semibold text-blue-600">0</span> pcs
        </p>
    </div>

    {{-- Quantity Adjustment --}}
    <label class="block font-medium mb-1">Quantity Adjustment <span class="text-red-500">*</span></label>
    <div class="flex items-center gap-4 mb-4">
        <input type="number" name="quantity" id="quantity" required
               class="border border-gray-300 p-2 rounded-lg flex-1 focus:ring-2 focus:ring-blue-500"
               placeholder="e.g., +10 or -5">
        <div class="flex gap-2">
            <button type="button" onclick="quickAdjust(5)" class="px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-sm">
                +5
            </button>
            <button type="button" onclick="quickAdjust(-5)" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-sm">
                -5
            </button>
        </div>
    </div>
    <p class="text-sm text-gray-500 mb-6">
        Use positive numbers to add stock, negative numbers to remove stock
    </p>

    {{-- Notes --}}
    <label class="block font-medium mb-1">Notes (Optional)</label>
    <textarea name="notes" rows="3"
              class="border border-gray-300 p-2 rounded-lg w-full mb-6 focus:ring-2 focus:ring-blue-500"
              placeholder="Reason for stock adjustment..."></textarea>

    {{-- Validation Message --}}
    <div id="validationMessage" class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg hidden"></div>

    <div class="flex gap-3">
      <button type="submit"
        class="bg-linear-to-r from-blue-600 to-indigo-600 text-white px-5 py-2 rounded-lg shadow hover:opacity-90">
        Process Adjustment
      </button>

      <a href="{{ route('stocks.index') }}"
         class="px-4 py-2 border rounded-lg hover:bg-gray-100">
         Cancel
      </a>
    </div>

  </form>
</div>

<script>
// Quick adjustment buttons
function quickAdjust(amount) {
    const currentInput = document.getElementById('quantity');
    const currentValue = parseInt(currentInput.value) || 0;
    currentInput.value = currentValue + amount;
}

// Update current stock when warehouse or product changes
document.getElementById('warehouse_id').addEventListener('change', updateCurrentStock);
document.getElementById('product_id').addEventListener('change', updateCurrentStock);

function updateCurrentStock() {
    const warehouseId = document.getElementById('warehouse_id').value;
    const productId = document.getElementById('product_id').value;
    const container = document.getElementById('currentStockContainer');
    const stockSpan = document.getElementById('currentStock');

    if (warehouseId && productId) {
        fetch(`/stock/${productId}/warehouse/${warehouseId}`)
            .then(response => response.json())
            .then(data => {
                stockSpan.textContent = data.current_stock;
                container.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching stock:', error);
                container.classList.add('hidden');
            });
    } else {
        container.classList.add('hidden');
    }
}

// Form validation
document.getElementById('stockForm').addEventListener('submit', function(e) {
    const quantity = parseInt(document.getElementById('quantity').value);
    const validationMessage = document.getElementById('validationMessage');
    
    if (isNaN(quantity)) {
        e.preventDefault();
        validationMessage.textContent = 'Please enter a valid quantity';
        validationMessage.classList.remove('hidden');
    } else if (quantity === 0) {
        e.preventDefault();
        validationMessage.textContent = 'Quantity cannot be zero';
        validationMessage.classList.remove('hidden');
    } else {
        validationMessage.classList.add('hidden');
    }
});
</script>

@endsection