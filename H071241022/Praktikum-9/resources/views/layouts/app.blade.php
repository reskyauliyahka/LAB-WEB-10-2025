<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Product Management')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- NAVBAR -->
    <nav class="bg-blue-600 text-white p-4 flex justify-between">
        <div class="font-bold text-lg">Product Management</div>
        <div class="space-x-4">
            <a href="{{ route('categories.index') }}" class="hover:underline">Categories</a>
            <a href="{{ route('warehouses.index') }}" class="hover:underline">Warehouses</a>
            <a href="{{ route('products.index') }}" class="hover:underline">Products</a>
            <a href="{{ route('stocks.index') }}" class="hover:underline">Stocks</a>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="max-w-6xl mx-auto mt-6 bg-white shadow-md rounded p-6">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>

</body>
</html>
