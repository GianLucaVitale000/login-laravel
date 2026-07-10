<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Registrazione</title>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body>
    <form class="card" method="POST" action="{{ route('register.attempt') }}">
        @csrf
        <h1>Crea un account</h1>

        <label for="name">Nome</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <label for="password_confirmation">Conferma password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <button type="submit">Registrati</button>

        <div class="link">
            Hai già un account? <a href="{{ route('login') }}">Accedi</a>
        </div>
    </form>
</body>
</html>
