<?php

namespace App\Http\Controllers;

use App\Models\Fish; // <-- Import Model Fish kita
use Illuminate\Http\Request; // <-- Import Request untuk validasi

class FishController extends Controller
{
    /**
     * Fitur A: Halaman Daftar Ikan (Index)
     * Menampilkan semua ikan dengan filter dan pagination.
     */
    public function index(Request $request)
    {
        // Ambil data untuk dropdown filter rarity
        $rarities = [
            'Common', 'Uncommon', 'Rare', 'Epic', 
            'Legendary', 'Mythic', 'Secret'
        ];

        // Mulai query, mirip [cite: 301]
        $query = Fish::query();

        // Terapkan filter rarity jika ada (Fitur A) [cite: 652]
        // Kita gunakan scope yang sudah dibuat di Model (scopeFilterByRarity)
        $query->filterByRarity($request->input('rarity'));

        // Ambil data ikan, urutkan dari yang terbaru,
        // dan terapkan pagination (Fitur A) [cite: 651]
        // Mirip dengan [cite: 311]
        $fishes = $query->latest()->paginate(10);

        // Kirim data ke view
        return view('fishes.index', compact('fishes', 'rarities'));
    }

    /**
     * Fitur B: Menampilkan form Tambah Ikan Baru (Create)
     */
    public function create()
    {
        // Ambil data untuk dropdown rarity
        $rarities = [
            'Common', 'Uncommon', 'Rare', 'Epic', 
            'Legendary', 'Mythic', 'Secret'
        ];
        
        // Tampilkan view form create [cite: 677]
        return view('fishes.create', compact('rarities'));
    }

    /**
     * Fitur B: Menyimpan Ikan Baru (Store)
     */
    public function store(Request $request)
    {
        // Validasi (Fitur B) [cite: 663-666]
        // Mirip dengan [cite: 322-329]
        $request->validate([
            'name' => 'required|string|max:100',
            'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
            'base_weight_min' => 'required|numeric|min:0',
            // 'gt:base_weight_min' berarti 'greater than' field base_weight_min [cite: 664]
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:0',
            // Validasi probabilitas antara 0.01 dan 100 [cite: 666]
            'catch_probability' => 'required|numeric|min:0.01|max:100',
            'description' => 'nullable|string', // Boleh kosong [cite: 665]
        ]);

        // Simpan data ke database, mirip [cite: 330]
        Fish::create($request->all());

        // Redirect kembali ke halaman index dengan pesan sukses
        // Mirip dengan [cite: 331-333]
        return redirect()->route('fishes.index')
                         ->with('success', 'Fish created successfully!');
    }

    /**
     * Fitur C: Lihat Detail Ikan (Show)
     */
    public function show(Fish $fish)
    {
        // Laravel otomatis mengambil data $fish berdasarkan ID di URL
        // Tampilkan view show [cite: 679]
        return view('fishes.show', compact('fish'));
    }

    /**
     * Fitur D: Menampilkan form Edit Ikan (Edit)
     */
    public function edit(Fish $fish)
    {
        // Ambil data untuk dropdown rarity
        $rarities = [
            'Common', 'Uncommon', 'Rare', 'Epic', 
            'Legendary', 'Mythic', 'Secret'
        ];

        // Tampilkan view edit dengan data $fish yang sudah ada [cite: 678]
        // Mirip dengan [cite: 341-344]
        return view('fishes.edit', compact('fish', 'rarities'));
    }

    /**
     * Fitur D: Menyimpan update Ikan (Update)
     */
    public function update(Request $request, Fish $fish)
    {
        // Validasi (sama seperti store) [cite: 670]
        // Mirip dengan [cite: 348-355]
        $request->validate([
            'name' => 'required|string|max:100',
            'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|min:0.01|max:100',
            'description' => 'nullable|string',
        ]);

        // Update data ikan yang ada, mirip [cite: 356]
        $fish->update($request->all());

        // Redirect kembali ke halaman index dengan pesan sukses
        // Mirip dengan [cite: 357-359]
        return redirect()->route('fishes.index')
                         ->with('success', 'Fish updated successfully!');
    }

    /**
     * Fitur E: Hapus Ikan (Delete)
     */
    public function destroy(Fish $fish)
    {
        // Hapus data ikan, mirip [cite: 362]
        $fish->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        // Mirip dengan [cite: 363-364]
        return redirect()->route('fishes.index')
                         ->with('success', 'Fish deleted successfully!');
    }
}