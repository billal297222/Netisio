<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
</head>
<body class="bg-light text-dark">

    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 py-5">

        <!-- Logo -->
        <div class="mb-4 text-center">
            <a href="/">
                <x-application-logo class="img-fluid" style="width: 80px; height: 80px;" />
            </a>
        </div>

        <!-- Content Card -->
        <div class="card shadow-sm w-100" style="max-width: 420px;">
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>

    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
