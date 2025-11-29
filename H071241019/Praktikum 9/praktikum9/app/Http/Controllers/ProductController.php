<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /** Display product list */
    public function index()
    {
        $products = Product::with(['category', 'warehouses', 'detail'])
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    /** Show form create */
    public function create()
    {
        $categories = Category::all();
        $warehouses = Warehouse::all();

        return view('products.create', compact('categories', 'warehouses'));
    }

    /** Store new product */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric|min:0',
            'warehouse_id'  => 'required|exists:warehouses,id',
            'quantity'      => 'required|integer|min:0',
            'weight'        => 'required|numeric|min:0', // AKTIFKAN
            'description'   => 'nullable|string',        // AKTIFKAN
            'size'          => 'nullable|string',        // AKTIFKAN
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create product
            $product = Product::create([
                'name'        => $request->name,
                'price'       => $request->price,
                'category_id' => $request->category_id,
            ]);

            // 2. CREATE PRODUCT DETAIL - AKTIFKAN
            ProductDetail::create([
                'product_id'  => $product->id,
                'description' => $request->description,
                'weight'      => $request->weight,
                'size'        => $request->size,
            ]);

            // 3. Add initial stock to warehouse
            if ($request->warehouse_id && $request->quantity > 0) {
                $product->warehouses()->attach($request->warehouse_id, [
                    'quantity' => $request->quantity
                ]);
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /** Show edit form */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $warehouses = Warehouse::all();
        
        // Load relationships
        $product->load(['detail', 'warehouses']);

        return view('products.edit', compact('product', 'categories', 'warehouses'));
    }

    /** Update product */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'          => 'required',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric|min:0',
            'weight'        => 'required|numeric|min:0',    // AKTIFKAN
            'description'   => 'nullable|string',           // AKTIFKAN
            'size'          => 'nullable|string',           // AKTIFKAN
        ]);

        DB::transaction(function () use ($request, $product) {
            // 1. Update product
            $product->update([
                'name'        => $request->name,
                'price'       => $request->price,
                'category_id' => $request->category_id,
            ]);

            // 2. Update or create product detail - AKTIFKAN
            if ($product->detail) {
                $product->detail->update([
                    'description' => $request->description,
                    'weight'      => $request->weight,
                    'size'        => $request->size,
                ]);
            } else {
                ProductDetail::create([
                    'product_id'  => $product->id,
                    'description' => $request->description,
                    'weight'      => $request->weight,
                    'size'        => $request->size,
                ]);
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    /** Show product detail */
    public function show(Product $product)
    {
        $product->load(['category', 'detail', 'warehouses']);
        return view('products.show', compact('product'));
    }

    /** Delete product */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}