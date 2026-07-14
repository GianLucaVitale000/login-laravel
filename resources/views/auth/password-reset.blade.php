<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Cambia Password</title>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body>
    <!-- Form per cambiare password -->
    <form class="card" method="POST" action="{{ route('password.update') }}">
            @csrf
            <h1>Cambia Password</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <label for="current_password">Password attuale</label>
            <input type="password" name="current_password" id="current_password" required autofocus>

            <label for="new_password">Nuova password</label>
            <input type="password" name="new_password" id="new_password" required>
            <small>
                La password deve contenere: lettere maiuscole e minuscole, numeri e simboli (min 8 caratteri)
            </small>

            <label for="new_password_confirmation">Conferma nuova password</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>

            <button type="submit" class="btn">Cambia Password</button>
            <a href="{{ route('dashboard') }}" class="link">Annulla</a>
        </form>
</body>
</html>