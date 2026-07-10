<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        .welcome { text-align: center; }
        .welcome h1 { font-size: 1.6rem; margin-bottom: 2rem; }
        .logout-form { margin-top: 0; }
    </style>
</head>
<body>
    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Cambia tema chiaro/scuro">
        <svg id="iconMoon" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
        <svg id="iconSun" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
        <span id="themeLabel">Scuro</span>
    </button>

    <div class="welcome">
        <h1>Benvenuto utente</h1>

        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <script>
        (function () {
            const root = document.documentElement;
            const toggleBtn = document.getElementById('themeToggle');
            const iconMoon = document.getElementById('iconMoon');
            const iconSun = document.getElementById('iconSun');
            const label = document.getElementById('themeLabel');
            const STORAGE_KEY = 'theme';

            function systemPrefersDark() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches;
            }

            function getEffectiveTheme() {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved === 'light' || saved === 'dark') return saved;
                return systemPrefersDark() ? 'dark' : 'light';
            }

            function applyTheme(theme) {
                root.setAttribute('data-theme', theme);
                if (theme === 'dark') {
                    iconMoon.style.display = 'none';
                    iconSun.style.display = '';
                    label.textContent = 'Light';
                } else {
                    iconMoon.style.display = '';
                    iconSun.style.display = 'none';
                    label.textContent = 'Dark';
                }
            }

            // Applica il tema corretto al caricamento della pagina
            applyTheme(getEffectiveTheme());

            // Click sul pulsante: inverte il tema e lo salva
            toggleBtn.addEventListener('click', function () {
                const current = getEffectiveTheme();
                const next = current === 'dark' ? 'light' : 'dark';
                localStorage.setItem(STORAGE_KEY, next);
                applyTheme(next);
            });
        })();
    </script>
</body>
</html>
