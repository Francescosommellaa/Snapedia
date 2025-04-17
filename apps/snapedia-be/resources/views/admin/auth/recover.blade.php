<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Recupero Password Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <h1 class="text-xl font-bold mb-4">🔐 Recupero Password Admin</h1>

        <form method="POST" action="{{ route('admin.auth.recover.handle') }}">
            @csrf

            <input type="hidden" name="step" value="{{ session('step', $step ?? 1) }}">
            <input type="hidden" name="email" value="{{ old('email', session('email')) }}">

            {{-- STEP 1 - EMAIL --}}
            @if (session('step', $step ?? 1) == 1)
                <label class="block mb-2">Inserisci la tua email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-2 border mb-1">
                @error('email')
                    <p class="text-red-600 text-sm mb-2">⚠️ {{ $message }}</p>
                @enderror
            @endif

            {{-- STEP 2 - OTP --}}
            @if (session('step') == 2)
                <p class="mb-2">Abbiamo inviato un codice a <strong>{{ session('email') }}</strong></p>
                <label class="block mb-2">Codice OTP</label>
                <input type="text" name="code" maxlength="6" class="w-full p-2 border mb-1" required>
                @error('code')
                    <p class="text-red-600 text-sm mb-2">⚠️ {{ $message }}</p>
                @enderror
            @endif

            {{-- STEP 3 - NUOVA PASSWORD --}}
            @if (session('step') == 3)
                <label class="block mb-2">Nuova Password</label>
                <input type="password" name="password" class="w-full p-2 border mb-1" required>
                @error('password')
                    <p class="text-red-600 text-sm mb-2">⚠️ {{ $message }}</p>
                @enderror

                <label class="block mb-2">Conferma Password</label>
                <input type="password" name="password_confirmation" class="w-full p-2 border mb-4" required>
            @endif

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Continua
            </button>
        </form>
    </div>
</body>
</html>
