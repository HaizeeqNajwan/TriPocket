<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TriPocket - Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-black/20 text-gray-900 backdrop-blur-md font-[Inter]">
    @include('profile.partials.auth-navbar') <!-- Extracted navbar -->

    <main class="pt-20"> <!-- Padding for fixed navbar -->
        @yield('content')
    </main>
</body>
</html>