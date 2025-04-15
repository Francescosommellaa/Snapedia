<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Snapedia Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <span class="font-bold">Snapedia Admin</span>
        <span class="text-sm text-gray-600">Loggato come {{ auth()->user()->email }}</span>
    </nav>

    <main class="max-w-3xl mx-auto mt-10">
        @yield('content')
    </main>
</body>
</html>