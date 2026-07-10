<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        body {
            justify-content: flex-start;
            align-items: stretch;
        }
        .welcome-message {
            color: var(--text-secondary);
            font-size: .95rem;
            margin-right: 1rem;
        }
    </style>
</head>
<body>
<header class="navbar">
    <div class="navbar-brand-toggle">
        <a href="{{ route('dashboard') }}" class="brand">
            <svg class="brand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2 2 7l10 5 10-5-10-5z"></path>
                <path d="M2 17l10 5 10-5"></path>
                <path d="M2 12l10 5 10-5"></path>
            </svg>
            <span>Acme</span>
        </a>
        <div class="navbar-right-items">
            <span class="welcome-message desktop-only">Benvenuto, {{ Auth::user()->name }}</span>
            <button class="icon-btn js-theme-toggle desktop-only" type="button" aria-label="Cambia tema chiaro/scuro">
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
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
            </button>

            <form class="desktop-only" method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="icon-btn" type="submit" aria-label="Esci">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Hamburger visibile solo da mobile -->
    <button class="hamburger mobile-only" id="hamburgerBtn" type="button" aria-label="Apri menu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</header>

<!-- Overlay scuro dietro la sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar mobile: stessa navbar, riorganizzata in verticale -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand-toggle">
            <a href="{{ route('dashboard') }}" class="brand">
                <svg class="brand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2 2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
                <span>Acme</span>
            </a>
            <button class="icon-btn js-theme-toggle" type="button" aria-label="Cambia tema chiaro/scuro">
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
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
            </button>
        </div>
        <span class="welcome-message">Benvenuto, {{ Auth::user()->name }}</span>
    </div>

    <div class="sidebar-actions">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="icon-btn" type="submit" aria-label="Esci">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </button>
        </form>
    </div>
</aside>

    <script>
    (function () {
        const root = document.documentElement;
        const STORAGE_KEY = 'theme';

        // --- Gestione tema (ora su piu pulsanti: desktop + sidebar) ---
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
            document.querySelectorAll('.js-theme-toggle').forEach(function (btn) {
                const moon = btn.querySelector('.icon-moon');
                const sun = btn.querySelector('.icon-sun');
                if (theme === 'dark') {
                    moon.style.display = 'none';
                    sun.style.display = '';
                } else {
                    moon.style.display = '';
                    sun.style.display = 'none';
                }
            });
        }

        applyTheme(getEffectiveTheme());

        document.querySelectorAll('.js-theme-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const next = getEffectiveTheme() === 'dark' ? 'light' : 'dark';
                localStorage.setItem(STORAGE_KEY, next);
                applyTheme(next);
            });
        });

        // --- Gestione apertura/chiusura sidebar mobile ---
        const hamburger = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            hamburger.setAttribute('aria-expanded', 'true');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            hamburger.setAttribute('aria-expanded', 'false');
        }

        hamburger.addEventListener('click', function () {
            const isOpen = sidebar.classList.contains('open');
            isOpen ? closeSidebar() : openSidebar();
        });

        overlay.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSidebar();
        });
    })();
</script>
</body>
</html>
