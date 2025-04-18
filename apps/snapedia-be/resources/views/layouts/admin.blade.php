<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snapedia Admin</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>

    @yield('content')

</body>
</html>