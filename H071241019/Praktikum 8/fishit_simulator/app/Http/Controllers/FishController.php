<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Http\Request;

class FishController extends Controller

{
    public function index()
    {
        $fishes = Fish::all();
        return view('fishes.index', compact('fishes'));
    }

    public function create()
{
    return view('fishes.create');
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'rarity' => 'required',
        'base_weight_min' => 'required|numeric',
        'base_weight_max' => 'required|numeric',
        'sell_price_per_kg' => 'required|integer',
        'catch_probability' => 'required|numeric',
        'description' => 'nullable',
    ]);

    Fish::create($request->all());

    return redirect()->route('fishes.index')->with('success', 'Ikan baru berhasil ditambahkan');
}
public function edit(Fish $fish)
{
    return view('fishes.edit', compact('fish'));
}

public function update(Request $request, Fish $fish)
{
    $request->validate([
        'name' => 'required',
        'rarity' => 'required',
        'base_weight_min' => 'required|numeric',
        'base_weight_max' => 'required|numeric',
        'sell_price_per_kg' => 'required|integer',
        'catch_probability' => 'required|numeric',
        'description' => 'nullable',
    ]);

    $fish->update($request->all());

    return redirect()->route('fishes.index')->with('success', 'Data ikan berhasil diperbarui');
}

public function destroy(Fish $fish)
{
    $fish->delete();

    return redirect()->route('fishes.index')->with('success', 'Ikan berhasil dihapus');
}
}
