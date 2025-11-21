<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Wajib import ini untuk transaction

class ProductController extends Controller
{
    public function index()
    {
        // Eager loading 'category' agar efisien
        $products = Product::with('category')->latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // Untuk dropdown kategori
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi input gabungan (Product + ProductDetail) [cite: 20]
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string', // detail
            'weight' => 'required|numeric|min:0', // detail
            'size' => 'nullable|string|max:255', // detail
        ]);

        // Gunakan Transaction [cite: 474]
        DB::transaction(function () use ($request) {
            // 1. Simpan data Produk
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
            ]);

            // 2. Simpan data Detail Produk lewat relasi [cite: 480]
            $product->detail()->create([
                'description' => $request->description,
                'weight' => $request->weight,
                'size' => $request->size,
            ]);
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('detail', 'category'); // Tampilkan detail lengkap [cite: 21]
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Validasi mirip store
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $product) {
            // Update Produk
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
            ]);

            // Update Detail (pakai updateOrCreate jaga-jaga kalau data detail blm ada) [cite: 519]
            $product->detail()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'description' => $request->description,
                    'weight' => $request->weight,
                    'size' => $request->size,
                ]
            );
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete(); // Detail otomatis terhapus karena 'cascade' di migration [cite: 6]
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}