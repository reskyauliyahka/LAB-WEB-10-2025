@extends('layouts.app')

@section('title', 'Edit Fish')

@section('content')
    <h2>Edit Fish: {{ $fish->name }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('fishes.update', $fish) }}" method="POST">
        @csrf
        @method('PUT') <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $fish->name) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="rarity" class="form-label">Rarity:</label>
                <select name="rarity" class="form-select">
                    @foreach($rarities as $rarity)
                        <option value="{{ $rarity }}" {{ old('rarity', $fish->rarity) == $rarity ? 'selected' : '' }}>
                            {{ $rarity }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="base_weight_min" class="form-label">Min Weight (kg):</label>
                <input type="number" step="0.01" name="base_weight_min" class="form-control" value="{{ old('base_weight_min', $fish->base_weight_min) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="base_weight_max" class="form-label">Max Weight (kg):</label>
                <input type="number" step="0.01" name="base_weight_max" class="form-control" value="{{ old('base_weight_max', $fish->base_weight_max) }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="sell_price_per_kg" class="form-label">Sell Price per Kg (Coins):</label>
                <input type="number" name="sell_price_per_kg" class="form-control" value="{{ old('sell_price_per_kg', $fish->sell_price_per_kg) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="catch_probability" class="form-label">Catch Probability (%):</label>
                <input type="number" step="0.01" name="catch_probability" class="form-control" value="{{ old('catch_probability', $fish->catch_probability) }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description (Optional):</label>
            <textarea class="form-control" name="description" rows="3">{{ old('description', $fish->description) }}</textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Fish</button>
            <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection