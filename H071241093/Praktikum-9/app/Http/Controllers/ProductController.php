<?php

namespace App\Http\Controllers;

use App\Models\Category; // Ganti nama
use App\Models\Product; // Ganti nama
use App\Models\ProductDetail; // Baru
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Kita butuh ini untuk transaction
use Illuminate\Support\Str; // Ini sudah ada

class ProductController extends Controller // Ganti nama class
{
    public function index(Request $request)
    {
        // Data toko ini kita biarkan saja
        $toko = [
            'nama_toko' => 'Toko Bunda El',
            'alamat' => 'Makassar, Sulawesi Selatan',
            'type' => 'ruko',
        ];

        $search = $request->keyword;

        // Query baru:
        // 1. Ganti Model Produk -> Product
        // 2. Ganti 'nama_produk' -> 'name'
        // 3. Hapus join manual, ganti pakai Eager Loading 'with('category')'
        // 4. Ganti 'kategori_id' -> 'category_id'
        $products = Product::with('category') // Eager loading relasi category
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->get();

        return view('pages.products.show', [ // Ganti view
            'data_toko' => $toko,
            'data_produk' => $products, // Kirim data dengan nama baru
        ]);
    }

    public function create()
    {
        // Ganti Model Kategori -> Category
        $data_kategori = Category::get();

        // Ganti view
        
        return view('pages.products.add', [
            'data_kategori' => $data_kategori // Ganti nama variabel
        ]);
    }

    public function store(Request $request)
    {
        // Validasi Sesuai Spek PDF (Product + ProductDetail)
        $request->validate([
            // Dari tabel Products
            'name' => 'required|min:5',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            // Dari tabel ProductDetails
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'size' => 'nullable|string',
        ], [
            'name.required' => 'Nama produk wajib diisi',
            'price.required' => 'Harga produk wajib diisi',
            'weight.required' => 'Berat produk wajib diisi',
        ]);

        // Mulai Database Transaction
        DB::beginTransaction();

        try {
            // 1. Simpan ke tabel 'products'
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
            ]);

            // 2. Simpan ke tabel 'product_details' menggunakan relasi
            $product->productDetail()->create([
                'description' => $request->description,
                'weight' => $request->weight,
                'size' => $request->size,
            ]);

            // Jika semua berhasil, commit transaction
            DB::commit();

            // Ganti redirect '/product' -> '/products'
            return redirect('/products')->with('message', 'Berhasil Menambahkan Data Produk');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua (rollback)
            DB::rollBack();
            // Kembali ke halaman form dengan error
            return redirect()->back()->withInput()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        // Ganti Model dan ambil juga relasinya
        $product = Product::with('category', 'productDetail')->findOrFail($id);

        // Ganti view
        return view('pages.products.detail', [
            'product' => $product // Ganti variabel
        ]);
    }

    public function edit(string $id)
    {
        // Ganti Model dan ambil juga relasinya
        $product = Product::with('productDetail')->findOrFail($id);
        $data_kategori = Category::get();

        // Ganti view
        return view('pages.products.edit', [
            'product' => $product, // Ganti variabel
            'kategori' => $data_kategori
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi Sesuai Spek PDF (Product + ProductDetail)
        $request->validate([
            'name' => 'required|min:5',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'size' => 'nullable|string',
        ]);

        // Mulai Database Transaction
        DB::beginTransaction();

        try {
            // 1. Cari Produk
            $product = Product::findOrFail($id);

            // 2. Update tabel 'products'
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
            ]);

            // 3. Update tabel 'product_details' (gunakan updateOrCreate agar aman)
            $product->productDetail()->updateOrCreate(
                ['product_id' => $product->id], // Cari berdasarkan product_id
                [ // Data yang di-update
                    'description' => $request->description,
                    'weight' => $request->weight,
                    'size' => $request->size,
                ]
            );

            // Jika semua berhasil, commit
            DB::commit();

            // Ganti redirect '/product' -> '/products'
            return redirect('/products')->with('message', 'Data Berhasil Di Edit');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua (rollback)
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors('Error: Gagal update. ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        // Ganti Model Produk -> Product
        $product = Product::findOrFail($id);
        
        // Hapus (relasi ke product_details dan pivot akan dihandle 'onDelete cascade')
        $product->delete();

        // Ganti redirect '/product' -> '/products'
        return redirect('/products')->with('message', 'Data Produk Berhasil dihapus!!');
    }
}