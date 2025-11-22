<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        // Mengambil data terbaru dengan pagination (mirip modul hal 12)
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    // Menampilkan form tambah kategori
    public function create()
    {
        return view('categories.create');
    }

    // Menyimpan data kategori baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }

    // Menampilkan detail kategori
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    // Menampilkan form edit kategori
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Memperbarui data kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    // Menghapus kategori
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Category deleted successfully.');
    }
}