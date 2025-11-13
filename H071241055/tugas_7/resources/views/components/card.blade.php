@props(['title', 'description', 'imageUrl'])

<div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl hover:scale-105">
    
    <img src="{{ $imageUrl ?? '/images/placeholder.jpg' }}" alt="{{ $title }}" class="w-full h-48 object-cover">
    
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-2 text-green-700">{{ $title }}</h3>
        <p class="text-gray-600 text-sm">{{ $description }}</p>
    </div>
</div>