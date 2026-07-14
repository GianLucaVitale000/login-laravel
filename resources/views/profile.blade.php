<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Profilo</title>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
    input[type="file"]::file-selector-button {
        background-color: #6C8CFF;
        color: #000;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 6px 12px;
        cursor: pointer;
        font-size: 0.9em;
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
                <div class="link"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <form method="POST" action="{{ route('logout') }}" style="margin-left: 15px;">
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
    </header>

    <!-- Sidebar mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="brand">
                <svg class="brand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2 2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
                <span>Acme</span>
            </a>
        </div>
        <div class="sidebar-actions">
            <div class="link" style="margin-bottom: 10px;"><a href="{{ route('dashboard') }}">Dashboard</a></div>
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

    <main style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <h1 style="margin-bottom: 20px; text-align: center;">Profilo Utente</h1>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; border-radius: 5px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Avatar corrente -->
        <div style="text-align: center; margin-bottom: 20px;">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width: 128px; height: 128px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;">
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 128px; height: 128px; margin-bottom: 10px; color: var(--text-secondary);">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            @endif
            <h2>{{ $user->name }}</h2>
            <p style="color: var(--text-secondary); margin: 5px 0;">{{ $user->email }}</p>
        </div>

        <!-- Form unificato -->
        <div class="card" style="padding: 20px; border-radius: 8px;">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label for="registered_at">Registrato il</label>
                <input type="text" id="registered_at" value="{{ $user->created_at->format('d/m/Y') }}" disabled readonly style="background: var(--bg-secondary); cursor: not-allowed;">

                <label for="password_changed_at">Ultimo cambio password</label>
                <input type="text" id="password_changed_at" value="@if($user->password_last_changed_at){{ $user->password_last_changed_at->format('d/m/Y H:i') }}@else Mai @endif" disabled readonly style="background: var(--bg-secondary); cursor: not-allowed;">

                <label for="name">Nome</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>

                <label for="avatar">Avatar (opzionale)</label>
                <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/jpg,image/gif">
                <small>Max 2MB. Formati: JPG, PNG, GIF</small>

                <button type="submit" class="btn" style="margin-top: 15px;">Salva Modifiche</button>
            </form>
        </div>

        <div class="link" style="text-align: center; margin-top: 20px;">
            <a href="{{ route('dashboard') }}">← Torna alla Dashboard</a>
        </div>
    </main>

    <script>
        (function () {
            const root = document.documentElement;
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
            }

            applyTheme(getEffectiveTheme());

            // Gestione sidebar mobile
            const hamburger = document.getElementById('hamburgerBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (hamburger && sidebar && overlay) {
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
            }
        })();
    </script>
</body>
</html>
