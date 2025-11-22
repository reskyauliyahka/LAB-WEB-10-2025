<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'weight'      => 'required|numeric|min:0',
            'size'        => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($data) {
            $product = Product::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'category_id' => $data['category_id'] ?? null,
            ]);

            ProductDetail::create([
                'product_id' => $product->id,
                'description' => $data['description'] ?? null,
                'weight' => $data['weight'],
                'size' => $data['size'] ?? null,
            ]);
        });

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        $product->load('category', 'detail', 'warehouses');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('detail');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'weight'      => 'required|numeric|min:0',
            'size'        => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($data, $product) {
            $product->update([
                'name' => $data['name'],
                'price' => $data['price'],
                'category_id' => $data['category_id'] ?? null,
            ]);

            $detail = $product->detail;
            if ($detail) {
                $detail->update([
                    'description' => $data['description'] ?? null,
                    'weight' => $data['weight'],
                    'size' => $data['size'] ?? null,
                ]);
            } else {
                ProductDetail::create([
                    'product_id' => $product->id,
                    'description' => $data['description'] ?? null,
                    'weight' => $data['weight'],
                    'size' => $data['size'] ?? null,
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted');
    }
}
