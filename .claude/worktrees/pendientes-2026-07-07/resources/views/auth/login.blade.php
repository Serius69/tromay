<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Iniciar sesión — Tromay</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            padding: 16px;
        }
        .card {
            background: #fff; border-radius: 12px; width: 100%; max-width: 400px;
            padding: 36px 32px; box-shadow: 0 20px 40px rgba(0,0,0,.35);
        }
        .brand { text-align: center; margin-bottom: 24px; }
        .brand h1 { font-size: 26px; letter-spacing: 2px; color: #203a43; }
        .brand p { font-size: 13px; color: #7a8a94; margin-top: 4px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #37474f; margin: 14px 0 6px; }
        input[type=email], input[type=password] {
            width: 100%; padding: 10px 12px; font-size: 15px;
            border: 1px solid #cfd8dc; border-radius: 8px; outline: none;
        }
        input:focus { border-color: #2c5364; box-shadow: 0 0 0 3px rgba(44,83,100,.15); }
        .remember { display: flex; align-items: center; gap: 8px; margin: 14px 0; font-size: 13px; color: #546e7a; }
        button {
            width: 100%; padding: 12px; margin-top: 6px; font-size: 15px; font-weight: 600;
            color: #fff; background: #2c5364; border: 0; border-radius: 8px; cursor: pointer;
        }
        button:hover { background: #203a43; }
        .errors {
            background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
            border-radius: 8px; padding: 10px 12px; font-size: 13px; margin-bottom: 8px;
        }
        .back { display: block; text-align: center; margin-top: 18px; font-size: 13px; color: #546e7a; text-decoration: none; }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">
            <h1>TROMAY</h1>
            <p>Panel administrativo — Casa de cambio</p>
        </div>

        @if ($errors->any())
            <div class="errors" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username">

            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">

            <div class="remember">
                <input id="remember" type="checkbox" name="remember" value="1">
                <label for="remember" style="margin:0;font-weight:400;">Mantener sesión iniciada</label>
            </div>

            <button type="submit">Iniciar sesión</button>
        </form>

        <a class="back" href="{{ route('home') }}">&larr; Volver al sitio público</a>
    </main>
</body>
</html>
