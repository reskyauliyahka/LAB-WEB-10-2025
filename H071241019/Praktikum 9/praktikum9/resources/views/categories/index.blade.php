@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            📂 Categories
        </h2>
        <a href="{{ route('categories.create') }}"
           class="px-5 py-2 bg-linear-to-r from-blue-500 to-blue-700 text-white rounded-lg shadow hover:opacity-90 transition">
            ➕ Add Category
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
                <tr>
                    <th class="p-3 border-b">#</th>
                    <th class="p-3 border-b">Name</th>
                    <th class="p-3 border-b">Description</th>
                    <th class="p-3 border-b text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $category)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="p-3 font-semibold">{{ $category->name }}</td>
                        <td class="p-3 text-gray-600">{{ $category->description ?? '-' }}</td>

                        <td class="p-3 text-center flex gap-2 justify-center">
                            <a href="{{ route('categories.edit', $category) }}"
                               class="px-4 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition">
                                ✏ Edit
                            </a>

                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')

                                <button class="px-4 py-1 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition">
                                    🗑 Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500 italic">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
