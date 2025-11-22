@extends('layouts.app')

@section('title', 'Detail: ' . $fish->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail: {{ $fish->name }}</h2>
        <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <strong>ID:</strong>
                <p>{{ $fish->id }}</p>
            </div>
            <div class="mb-3">
                <strong>Rarity:</strong>
                <p>{{ $fish->rarity }}</p>
            </div>
            <div class="mb-3">
                <strong>Weight Range:</strong>
                <p>{{ $fish->formatted_weight_range }}</p>
            </div>
            <div class="mb-3">
                <strong>Sell Price per Kg:</strong>
                <p>{{ $fish->formatted_price }}</p>
            </div>
            <div class="mb-3">
                <strong>Catch Probability:</strong>
                <p>{{ $fish->catch_probability }}%</p>
            </div>
            <div class="mb-3">
                <strong>Description:</strong>
                <p>{{ $fish->description ?? 'N/A' }}</p>
            </div>
            <div class="mb-3">
                <strong>Created At:</strong>
                <p>{{ $fish->created_at }}</p>
            </div>
            <div class="mb-3">
                <strong>Last Updated:</strong>
                <p>{{ $fish->updated_at }}</p>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning">Edit</a>
            <form method="POST" action="{{ route('fishes.destroy', $fish) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                        onclick="return confirm('Are you sure you want to delete this fish?')">Delete</button>
            </form>
        </div>
    </div>
@endsection