<?php

namespace App\Http\Controllers; // <-- BENAR (pakai backslash)

use App\Models\Warehouse;
use Illuminate\Http\Request;
// Kita perlu tambahkan 'use Controller' yang benar
use App\Http\Controllers\Controller; 

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->keyword;

        // Ambil data gudang, mirip seperti kategori
        $warehouses = Warehouse::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('location', 'like', "%{$search}%");
        })->get();

        return view('pages.warehouses.show', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.warehouses.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi sesuai spek PDF
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ], [
            'name.required' => 'Nama gudang wajib diisi.',
        ]);

        Warehouse::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect('/warehouses')->with('message', 'Berhasil Menambahkan Data Gudang');
    }

    /**
     * Display the specified resource.
     * (Tidak diminta di soal, tapi kita buatkan)
     */
    public function show(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        // Pastikan view detail ini ada, atau hapus fungsi ini jika tidak perlu
        // return view('pages.warehouses.detail', compact('warehouse')); 
        return redirect('/warehouses'); // Kita redirect saja
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('pages.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi sesuai spek PDF
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ], [
            'name.required' => 'Nama gudang wajib diisi.',
        ]);

        Warehouse::findOrFail($id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect('/warehouses')->with('message', 'Berhasil Mengubah Data Gudang');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        // Hapus (relasi stok di pivot table akan dihandle 'onDelete cascade')
        $warehouse->delete();

        return redirect('/warehouses')->with('message', 'Data Gudang Berhasil dihapus!!');
    }
}