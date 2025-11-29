@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8 space-y-6">

    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        ✏️ Edit Category
    </h2>

    <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div>
            <label class="font-semibold block mb-1">Category Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                   class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        {{-- Description --}}
        <div>
            <label class="font-semibold block mb-1">Description</label>
            <textarea name="description" rows="4"
                      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description', $category->description) }}</textarea>
        </div>

        {{-- Actions --}}
        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Cancel
            </a>

            <button
                class="px-6 py-2 bg-linear-to-r from-green-500 to-green-700 text-white rounded-lg shadow hover:opacity-90 transition">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
