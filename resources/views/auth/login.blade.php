<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f5f5f5; }
        form { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.1); width: 300px; }
        label { display: block; margin-top: 1rem; margin-bottom: .25rem; }
        input { width: 100%; padding: .5rem; box-sizing: border-box; }
        button { margin-top: 1.5rem; width: 100%; padding: .6rem; background: #1e293b; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: #c00; font-size: .85rem; margin-top: .5rem; }
        .link { text-align: center; margin-top: 1rem; font-size: .9rem; }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <h2>Accedi</h2>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <button type="submit">Accedi</button>

        <div class="link">
            Non hai un account? <a href="{{ route('register') }}">Registrati</a>
        </div>
    </form>
</body>
</html>
