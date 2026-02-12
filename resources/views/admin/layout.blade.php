<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-database-compat.js"></script>
</head>
<body class="bg-gray-100">

<div class="flex">
    {{-- Sidebar --}}
    @include('admin.sidebar')

    {{-- Content --}}
    <main class="ml-64 w-full p-6">
        @yield('content')
    </main>
</div>

</body>
</html>