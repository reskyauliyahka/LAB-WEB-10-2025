@extends('layouts.app')

@section('title', 'Fish Database')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Fish Database</h2>
        <a href="{{ route('fishes.create') }}" class="btn btn-primary">Add New Fish</a>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <form method="GET" action="{{ route('fishes.index') }}">
                <select name="rarity" class="form-select" onchange="this.form.submit()">
                    <option value="">All Rarities</option>
                    @foreach($rarities as $rarity)
                        <option value="{{ $rarity }}" {{ request('rarity') == $rarity ? 'selected' : '' }}>
                            {{ $rarity }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Rarity</th>
                            <th>Weight Range</th>
                            <th>Sell Price</th>
                            <th>Probability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fishes as $fish)
                            <tr>
                                <td>{{ $fish->id }}</td>
                                <td>{{ $fish->name }}</td>
                                <td>{{ $fish->rarity }}</td>
                                <td>{{ $fish->formatted_weight_range }}</td>
                                <td>{{ $fish->formatted_price }}</td>
                                <td>{{ $fish->catch_probability }}%</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('fishes.show', $fish) }}" class="btn btn-info rounded">View</a>
                                        <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning mx-2 rounded">Edit</a>
                                        <form method="POST" action="{{ route('fishes.destroy', $fish) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Apakah kamu serius ingin menghapus ikan ini?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No fishes found.</td>
                            </tr>
                        @endforelse 
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $fishes->links() }}
    </div>
@endsection