<?php

namespace App\Http\Controllers; // <-- BENAR (pakai backslash)

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// Kita perlu tambahkan 'use Controller' yang benar
use App\Http\Controllers\Controller; 

class StockController extends Controller
{
    /**
     * Menampilkan halaman index stok (Poin 4. Index)
     * Lengkap dengan filter gudang (Poin 4. Filter)
     */
    public function index(Request $request)
    {
        $selectedWarehouseId = $request->input('warehouse_id');

        // 1. Ambil semua gudang (untuk dropdown filter)
        $warehouses = Warehouse::orderBy('name')->get();

        // 2. Ambil data stok dari tabel pivot
        $stockData = DB::table('products_warehouses')
            ->join('products', 'products_warehouses.product_id', '=', 'products.id')
            ->join('warehouses', 'products_warehouses.warehouse_id', '=', 'warehouses.id')
            ->when($selectedWarehouseId, function ($query, $warehouseId) {
                return $query->where('products_warehouses.warehouse_id', $warehouseId);
            })
            ->select(
                'products.name as product_name',
                'warehouses.name as warehouse_name',
                'products_warehouses.quantity'
            )
            ->orderBy('warehouse_name')
            ->orderBy('product_name')
            ->get();

        // 3. Kirim data ke view
        return view('pages.stock.index', [
            'warehouses' => $warehouses,
            'stockData' => $stockData,
            'selectedWarehouseId' => $selectedWarehouseId
        ]);
    }

    /**
     * [BARU] Menampilkan form transfer stok (Poin 4. Transfer Stok)
     */
    public function showTransferForm()
    {
        // Ambil semua gudang dan produk untuk dropdown
        $warehouses = Warehouse::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('pages.stock.transfer', [
            'warehouses' => $warehouses,
            'products' => $products
        ]);
    }

    /**
     * [BARU] Memproses logika transfer stok (Poin 4. Transfer Stok)
     */
    public function transfer(Request $request)
    {
        // 1. Validasi input form
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|not_in:0', // Kuantitas harus angka, tidak boleh 0
        ], [
            'quantity.not_in' => 'Kuantitas tidak boleh 0.',
        ]);

        $warehouseId = $request->warehouse_id;
        $productId = $request->product_id;
        $quantityChange = (int) $request->quantity; // Ubah jadi integer

        // Mulai database transaction
        DB::beginTransaction();
        try {
            // 2. Cari data stok yang ada
            // 'lockForUpdate' PENTING untuk mencegah race condition
            $stock = DB::table('products_warehouses')
                ->where('warehouse_id', $warehouseId)
                ->where('product_id', $productId)
                ->lockForUpdate() // Kunci baris ini agar tidak bisa diubah proses lain
                ->first();

            $currentStock = $stock ? $stock->quantity : 0; // Stok saat ini, 0 jika belum ada
            $newStock = $currentStock + $quantityChange; // Stok baru

            // 3. Validasi utama: Stok tidak boleh minus (sesuai soal)
            if ($newStock < 0) {
                // Batalkan transaksi
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Transfer Gagal! Stok akhir akan minus ($newStock). Stok saat ini adalah $currentStock.");
            }

            // 4. Update atau Buat (Upsert) data stok
            DB::table('products_warehouses')->upsert(
                [
                    // Data yang dicari (unique key)
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    // Data yang di-update atau di-insert
                    'quantity' => $newStock
                ],
                ['product_id', 'warehouse_id'], // Kolom unique untuk dicari
                ['quantity'] // Kolom yang di-update
            );

            // 5. Jika sukses, commit transaksi
            DB::commit();

            return redirect()->route('stock.index')
                ->with('message', "Berhasil transfer stok! Stok baru: $newStock");

        } catch (\Exception $e) {
            // Jika ada error lain, batalkan
            DB::rollBack();
            Log::error('Error Transfer Stok: ' . $e->getMessage()); // Catat error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi error: ' . $e->getMessage());
        }
    }
}