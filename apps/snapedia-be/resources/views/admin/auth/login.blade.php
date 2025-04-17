<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | Snapedia</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #f6f6f6; }
        form { background: white; padding: 2rem; max-width: 400px; margin: auto; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; font-size: 1rem; }
        .error { color: red; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('admin.auth.login.submit') }}">
        @csrf
        <h1>🔐 Login Admin</h1>

        <input type="text" name="login" placeholder="Email o Username" value="{{ old('login') }}" required>
        @error('login')
            <p class="text-sm text-red-600 mb-1">⚠️ {{ $message }}</p>
        @enderror

        <input type="password" name="password" placeholder="Password" required>
        @error('password')
            <p class="text-sm text-red-600 mb-2">⚠️ {{ $message }}</p>
        @enderror

        <button type="submit">Accedi</button>

        <p class="text-sm text-right mt-2">
            <a href="{{ route('admin.auth.recover.form') }}" class="text-blue-600 hover:underline">Hai dimenticato la password?</a>
        </p>
        <p class="text-sm text-right mt-2">
            <a href="{{ route('admin.auth.register.show') }}" class="text-purple-700 hover:underline">Registrati come Admin</a>
        </p>
    </form>
</body>
</html>
