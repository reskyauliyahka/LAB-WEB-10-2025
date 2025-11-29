<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /** 
     * Index: menampilkan tabel list produk per gudang + filter gudang 
     * DAN total (sum) masing-masing produk dalam gudang tersebut
     */
    public function index(Request $request)
    {
        $warehouses = Warehouse::all();
        $selectedWarehouse = $request->get('warehouse_id');
        
        // Query untuk stock per gudang
        $stocksQuery = DB::table('products_warehouses')
            ->join('products', 'products_warehouses.product_id', '=', 'products.id')
            ->join('warehouses', 'products_warehouses.warehouse_id', '=', 'warehouses.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products_warehouses.*',
                'products.name as product_name',
                'products.price',
                'categories.name as category_name',
                'warehouses.name as warehouse_name'
            );

        // Filter by warehouse jika dipilih
        if ($selectedWarehouse) {
            $stocksQuery->where('products_warehouses.warehouse_id', $selectedWarehouse);
        }

        $stocks = $stocksQuery->orderBy('warehouse_name')
            ->orderBy('product_name')
            ->paginate(15);

        // Hitung total stock per warehouse untuk summary
        $warehouseTotals = DB::table('products_warehouses')
            ->join('warehouses', 'products_warehouses.warehouse_id', '=', 'warehouses.id')
            ->when($selectedWarehouse, function ($query) use ($selectedWarehouse) {
                return $query->where('warehouse_id', $selectedWarehouse);
            })
            ->select('warehouses.name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('warehouses.id', 'warehouses.name')
            ->get();

        return view('stocks.index', compact('stocks', 'warehouses', 'selectedWarehouse', 'warehouseTotals'));
    }

    /** 
     * Transfer Stok: form dengan inputan gudang, produk, dan nilai stok (+/-)
     * Validasi: stok tidak boleh minus
     */
    public function create()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();

        return view('stocks.create', compact('products', 'warehouses'));
    }

    /** Process stock transfer/adjustment */
    /** Process stock transfer/adjustment */
public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'warehouse_id' => 'required|exists:warehouses,id',
        'quantity' => 'required|integer',
        'notes' => 'nullable|string|max:255'
    ]);

    DB::transaction(function () use ($request) {
        $productId = $request->product_id;
        $warehouseId = $request->warehouse_id;
        $quantityChange = $request->quantity;
        $notes = $request->notes;

        // Cek apakah produk sudah ada di gudang
        $existingStock = DB::table('products_warehouses')
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        $currentQuantity = $existingStock ? $existingStock->quantity : 0;
        $newQuantity = $currentQuantity + $quantityChange;

        // VALIDASI: Stok tidak boleh minus
        if ($newQuantity < 0) {
            throw new \Exception("Stock cannot be negative. Current stock: {$currentQuantity}, attempted change: {$quantityChange}");
        }

        if ($existingStock) {
            // Update stock existing
            DB::table('products_warehouses')
                ->where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->update(['quantity' => $newQuantity]);
        } else {
            // Buat stock baru (hanya jika quantity positif)
            if ($quantityChange > 0) {
                DB::table('products_warehouses')->insert([
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'quantity' => $quantityChange,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                throw new \Exception("Cannot remove stock from a warehouse that doesn't have the product");
            }
        }

        // COMMENT DULU Stock Movement sampai tabelnya fixed
        // if (class_exists('App\Models\StockMovement')) {
        //     StockMovement::create([...]);
        // }
    });

    return redirect()->route('stocks.index')
        ->with('success', 'Stock adjustment completed successfully!');
}

    /** Get current stock for a product in a warehouse (for AJAX) */
    public function getStock($productId, $warehouseId)
    {
        $stock = DB::table('products_warehouses')
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        return response()->json([
            'current_stock' => $stock ? $stock->quantity : 0
        ]);
    }
}