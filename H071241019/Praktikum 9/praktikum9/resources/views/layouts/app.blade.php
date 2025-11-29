<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Inventory System</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md p-4 mb-6">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-blue-600">📦 Inventory System</h1>

            <ul class="flex space-x-6 text-gray-700 font-medium">
                <li><a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a></li>
                <li><a href="{{ route('categories.index') }}" class="hover:text-blue-600">Categories</a></li>
                <li><a href="{{ route('warehouses.index') }}" class="hover:text-blue-600">Warehouses</a></li>
                <li><a href="{{ route('stocks.index') }}" class="hover:text-blue-600">Stock Movement</a></li>
            </ul>
        </div>
    </nav>


    <div class="container mx-auto px-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR MESSAGE FIXED --}}
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 shadow">
                <ul class="list-disc pl-6 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- PAGE CONTENT --}}
        <main class="bg-white p-6 shadow-md rounded-md">
            @yield('content')
        </main>

    </div>

</body>
</html>
