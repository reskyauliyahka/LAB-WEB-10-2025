<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::all();
        
        // Filter berdasarkan gudang jika ada input [cite: 24]
        $selectedWarehouseId = $request->query('warehouse_id');
        
        $products = Product::query();

        if ($selectedWarehouseId) {
            // Ambil produk yang HANYA ada di gudang terpilih
            $products->whereHas('warehouses', function($q) use ($selectedWarehouseId) {
                $q->where('warehouse_id', $selectedWarehouseId);
            });
        }
        
        // Load relasi warehouses (dan pivot quantity) untuk ditampilkan di tabel [cite: 23]
        $products = $products->with(['warehouses' => function($query) use ($selectedWarehouseId) {
            if ($selectedWarehouseId) {
                $query->where('warehouse_id', $selectedWarehouseId);
            }
        }])->get();

        return view('stocks.index', compact('products', 'warehouses', 'selectedWarehouseId'));
    }

    public function transferForm()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        return view('stocks.transfer', compact('products', 'warehouses'));
    }

    public function processTransfer(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|not_in:0', // Input bisa positif/negatif tapi tidak 0 
        ]);

        $product = Product::find($request->product_id);
        $warehouseId = $request->warehouse_id;
        $change = (int) $request->quantity;

        // Cek stok saat ini di gudang tersebut
        $pivot = $product->warehouses()->where('warehouse_id', $warehouseId)->first();
        $currentStock = $pivot ? $pivot->pivot->quantity : 0;

        // Logika: Jangan sampai minus 
        if (($currentStock + $change) < 0) {
            return back()->with('error', "Stok tidak mencukupi! Stok saat ini: $currentStock, Pengurangan: $change");
        }

        if ($pivot) {
            // Update stok yang sudah ada
            $product->warehouses()->updateExistingPivot($warehouseId, [
                'quantity' => $currentStock + $change
            ]);
        } else {
            // Jika barang belum pernah ada di gudang ini, buat baru (hanya jika penambahan positif)
            if ($change > 0) {
                $product->warehouses()->attach($warehouseId, ['quantity' => $change]);
            } else {
                return back()->with('error', 'Produk belum ada di gudang, tidak bisa dikurangi.');
            }
        }

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil diperbarui.');
    }
}