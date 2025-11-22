<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $warehouseId = $request->query('warehouse_id');

        $query = ProductWarehouse::with('product', 'warehouse');
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        $stocks = $query->paginate(25);
        return view('stocks.index', compact('warehouses', 'stocks', 'warehouseId'));
    }

    public function transferForm()
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('stocks.transfer', compact('warehouses', 'products'));
    }

    public function transfer(Request $request)
    {
        $data = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id'   => 'required|exists:products,id',
            'quantity'     => 'required|integer',
        ]);

        DB::transaction(function () use ($data) {
            $stock = ProductWarehouse::firstOrCreate([
                'warehouse_id' => $data['warehouse_id'],
                'product_id' => $data['product_id'],
            ]);

            $newQty = $stock->quantity + $data['quantity'];

            if ($newQty < 0) {
                abort(400, 'Stock cannot be negative.');
            }

            $stock->update(['quantity' => $newQty]);
        });

        return redirect()->route('stocks.index')->with('success', 'Stock transfer successful');
    }
}
