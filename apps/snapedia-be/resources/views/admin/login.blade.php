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
    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <h1>🔐 Login Admin</h1>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <input type="text" name="login" placeholder="Email o Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <label>
            <input type="checkbox" name="remember"> Ricordami
        </label>

        <button type="submit">Accedi</button>
    </form>
</body>
</html>

