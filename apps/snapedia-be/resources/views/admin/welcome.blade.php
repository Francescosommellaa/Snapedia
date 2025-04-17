<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Benvenuto Admin | Snapedia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #f0f2f5; text-align: center; }
        .box { background: white; display: inline-block; padding: 2rem; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 1rem; }
        p { margin-bottom: 2rem; }
        form, a { display: block; margin-top: 1rem; }
        button { padding: 10px 20px; font-size: 1rem; cursor: pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🎓 Area Admin Snapedia</h1>
        <p>Benvenuto nella dashboard riservata agli amministratori</p>

        <form action="{{ route('admin.auth.register.redirect') }}" method="POST">
            @csrf
            <button type="submit">📝 Registrati come Admin</button>
        </form>

        <a href="{{ route('admin.auth.login.form') }}">
            <button>🔐 Accedi</button>
        </a>
    </div>

    @if(session('error'))
        <p style="color: red; margin-top: 2rem;">⚠️ {{ session('error') }}</p>
    @endif
</body>
</html>
