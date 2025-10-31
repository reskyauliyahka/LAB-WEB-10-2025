<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FishController extends Controller
{
    public function index(Request $request)
    {
        $rarityFilter = $request->input('rarity');
        $rarities = Fish::$rarities;

        $fishesQuery = Fish::query();

        if ($rarityFilter) {
            $fishesQuery->where('rarity', $rarityFilter);
        }

        $fishes = $fishesQuery->latest()->paginate(5)
            ->appends($request->query()); // Agar filter tetap ada saat ganti halaman

        return view('fishes.index', compact('fishes', 'rarities', 'rarityFilter'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $rarities = Fish::$rarities;
        return view('fishes.create', compact('rarities'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'rarity' => ['required', Rule::in(Fish::$rarities)],
            'base_weight_min' => 'required|numeric|min:0.01|lte:base_weight_max',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|between:0.01,100.00',
            'description' => 'nullable|string',
        ]);

        Fish::create($validatedData);

        return redirect()->route('fishes.index');
    }

    public function show(Fish $fish)
    {
        return view('fishes.show', compact('fish'));
    }

    public function edit(Fish $fish)
    {
        $rarities = Fish::$rarities;
        return view('fishes.edit', compact('fish', 'rarities'));
    }

    public function update(Request $request, Fish $fish)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'rarity' => ['required', Rule::in(Fish::$rarities)],
            'base_weight_min' => 'required|numeric|min:0.01|lte:base_weight_max',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|between:0.01,100.00',
            'description' => 'nullable|string',
        ]);

        $fish->update($validatedData);

        return redirect()->route('fishes.index');
    }

    public function destroy(Fish $fish)
    {
        $fish->delete();

        return redirect()->route('fishes.index');
    }
}

