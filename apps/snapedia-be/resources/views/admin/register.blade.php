<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8">
    <title>Registrazione Admin | Snapedia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #f6f6f6; }
        form { background: white; padding: 2rem; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; font-size: 1rem; }
        .error { color: red; margin-bottom: 1rem; }
    </style>
  </head>
  <body>
    <h1>🛡️ Crea il tuo account Admin</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $step = session('step', 1);
        $email = session('email');
    @endphp

    @if ($step === 1)
        <form method="POST" action="{{ url('/admin/register/email') }}">
            @csrf
            <label for="email">Email</label>
            <input type="email" name="email" required placeholder="Inserisci la tua email">
            <button type="submit">Invia codice di verifica</button>
        </form>
    @endif

    @if ($step === 2)
    <div style="max-width: 500px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1);">

      {{-- 🟦 FORM 1 - Verifica OTP --}}
      <form method="POST" action="{{ url('/admin/register/verify') }}">
          @csrf
          <input type="hidden" name="email" value="{{ $email }}">

          <label for="code">Inserisci il codice inviato all'email <strong>{{ $email }}</strong></label>
          <input type="text" name="code" id="code" maxlength="6" placeholder="Es. 123456" required>

          <button type="submit" style="margin-top: 10px;">Verifica codice</button>
      </form>

      {{-- 🟨 FORM 2 - Invia nuovo codice --}}
      <form method="POST" action="{{ url('/admin/register/email') }}">
          @csrf
          <input type="hidden" name="email" value="{{ $email }}">

          <button type="submit" id="resendBtn" style="margin-top: 10px;" disabled>
              Invia nuovo codice (<span id="countdown">{{ session('cooldown', 30) }}</span>s)
          </button>
      </form>
    </div>

      <script>
        let seconds = {{ session('cooldown', 30) }};
        const countdown = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');
        const timer = setInterval(() => {
            seconds--;
            countdown.innerText = seconds;
            if (seconds <= 0) {
                resendBtn.disabled = false;
                resendBtn.innerText = 'Invia nuovo codice';
                clearInterval(timer);
            }
        }, 1000);
      </script>
    @endif

    @if ($step === 3)
        <form method="POST" action="{{ url('/admin/register/finalize') }}" id="finalStepForm">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <label for="name">Nome</label>
            <input type="text" name="name" id="name" required placeholder="Es. Mario">
            <div class="error" id="error-name"></div>

            <label for="surname">Cognome</label>
            <input type="text" name="surname" id="surname" required placeholder="Es. Rossi">
            <div class="error" id="error-surname"></div>

            <label for="username">Username</label>
            <input type="text" name="username" id="username" required placeholder="3-18 caratteri, no simboli">
            <div class="error" id="error-username"></div>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required placeholder="Min 8 caratteri, 1 maiuscola, numero">
            <div class="error" id="error-password"></div>

            <label for="password_confirmation">Conferma Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            <div class="error" id="error-password_confirmation"></div>

            <button type="submit" id="submitBtn" disabled>Crea Account Admin</button>
        </form>

        <script>
            const form = document.getElementById('finalStepForm');
            const submitBtn = document.getElementById('submitBtn');

            const validators = {
                name: value => value.trim().length > 1,
                surname: value => value.trim().length > 1,
                username: value => /^[a-zA-Z0-9]{3,18}$/.test(value),
                password: value => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(value),
                password_confirmation: (value, data) => value === data.password
            };
          
            const errors = {
                name: "Inserisci un nome valido",
                surname: "Inserisci un cognome valido",
                username: "Username da 3 a 18 caratteri, solo lettere e numeri",
                password: "Min 8 caratteri, 1 maiuscola, 1 numero",
                password_confirmation: "Le password non coincidono"
            };
          
            const inputs = form.querySelectorAll("input");
            let state = {};
          
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    state[input.name] = input.value;
                    validateForm();
                });
            });
          
            function validateForm() {
                let isValid = true;
            
                for (const field in validators) {
                    const input = document.getElementById(field);
                    const errorDiv = document.getElementById('error-' + field);
                    const value = input.value;
                    const valid = validators[field](value, state);
                
                    if (!valid) {
                        isValid = false;
                        errorDiv.textContent = errors[field];
                        input.style.borderColor = 'red';
                    } else {
                        errorDiv.textContent = '';
                        input.style.borderColor = '#ccc';
                    }
                }
              
                submitBtn.disabled = !isValid;
            }
        </script>

        <style>
            input { border: 1px solid #ccc; }
            input:focus { outline: none; border-color: #888; }
            .error { font-size: 0.85rem; color: red; margin-top: -8px; margin-bottom: 10px; }
            button[disabled] { background-color: #ccc; cursor: not-allowed; }
        </style>
    @endif
  </body>
</html>
