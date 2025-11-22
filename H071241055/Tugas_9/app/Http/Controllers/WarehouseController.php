<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(10);
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    // Menyimpan data warehouse baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);

        Warehouse::create($request->all());

        return redirect()->route('warehouses.index')
                         ->with('success', 'Warehouse created successfully.');
    }

    // Menampilkan detail warehouse (opsional, tapi standar resource)
    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

     //Menampilkan form edit warehouse [cite: 669]
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    // Memperbarui data warehouse
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);

        $warehouse->update($request->all());

        return redirect()->route('warehouses.index')
                         ->with('success', 'Warehouse updated successfully.');
    }

    // Menghapus warehouse
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')
                         ->with('success', 'Warehouse deleted successfully.');
    }
}