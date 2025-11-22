<!DOCTYPE html>
<html>
<head>
    <title>Fish It Simulator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a href="{{ route('fishes.index') }}" class="navbar-brand">Fish It Simulator</a>
    </div>
</nav>
<div class="container mt-4">
    @yield('content')
</div>
</body>
</html>
