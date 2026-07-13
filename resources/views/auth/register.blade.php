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

        <!-- reCAPTCHA v2 Widget -->
        <div style="margin: 1rem 0; height: 64px; overflow: hidden">
            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"
            style="transform: scale(0.84); transform-origin: 0 0;"></div>
        </div>

        @error('g-recaptcha-response')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Registrati</button>

        <div class="link">
            Hai già un account? <a href="{{ route('login') }}">Accedi</a>
        </div>
    </form>

    <!-- Script reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Controlla se il tema è dark (da prefers-color-scheme o data-theme)
    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches ||
    document.documentElement.getAttribute('data-theme') === 'dark';

    const recaptcha = document.querySelector('.g-recaptcha');
    if (recaptcha && isDark) {
        recaptcha.setAttribute('data-theme', 'dark');
    }
});
</script>
</body>
</html>
