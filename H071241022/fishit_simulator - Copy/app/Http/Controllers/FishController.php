<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Http\Request;

class FishController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $rarity = $request->input('rarity');
        $query = Fish::query();

        if ($rarity) {
            $query->where('rarity', $rarity);
        }

        $fishes = $query->paginate(5);
        return view('fishes.index', compact('fishes', 'rarity'));
    }

    // CREATE
    public function create()
    {
        return view('fishes.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'rarity' => 'required',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:1',
            'catch_probability' => 'required|numeric|between:0.01,100',
        ]);

        Fish::create($request->all());
        return redirect()->route('fishes.index')->with('success', 'Ikan berhasil ditambahkan!');
    }

    // SHOW
    public function show(Fish $fish)
    {
        return view('fishes.show', compact('fish'));
    }

    // EDIT
    public function edit(Fish $fish)
    {
        return view('fishes.edit', compact('fish'));
    }

    // UPDATE
    public function update(Request $request, Fish $fish)
    {
        $request->validate([
            'name' => 'required|max:100',
            'rarity' => 'required',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:1',
            'catch_probability' => 'required|numeric|between:0.01,100',
        ]);

        $fish->update($request->all());
        return redirect()->route('fishes.index')->with('success', 'Data ikan berhasil diupdate!');
    }

    // DESTROY
    public function destroy(Fish $fish)
    {
        $fish->delete();
        return redirect()->route('fishes.index')->with('success', 'Ikan berhasil dihapus!');
    }
}
