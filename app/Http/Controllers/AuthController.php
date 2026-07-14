<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    /**
     * Mostra il form di login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

        /**
     * Gestisce il tentativo di login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->input('email');
        $ip = $request->ip();
        $identifier = md5($email . '|' . $ip);
        $ipOnlyIdentifier = md5($ip);

        // 1. Controlla blocco per email+IP
        $attemptData = Redis::get("login_attempts:{$identifier}");
        if ($attemptData) {
            $data = json_decode($attemptData, true);
            if (isset($data['locked_until']) && $data['locked_until'] > time()) {
                $minutes = ceil(($data['locked_until'] - time()) / 60);
                return back()
                    ->withErrors(['email' => "Troppe richieste. Riprova tra $minutes minuti."])
                    ->onlyInput('email');
            }
        }

        // 2. Controlla blocco per SOLO IP (anti user enumeration / brute-force multi-account)
        $ipAttemptsData = Redis::get("login_attempts_ip:{$ipOnlyIdentifier}");
        if ($ipAttemptsData) {
            $ipData = json_decode($ipAttemptsData, true);
            if (isset($ipData['locked_until']) && $ipData['locked_until'] > time()) {
                $minutes = ceil(($ipData['locked_until'] - time()) / 60);
                return back()
                    ->withErrors(['email' => "Troppe richieste dal tuo indirizzo. Riprova tra $minutes minuti."])
                    ->onlyInput('email');
            }
        }

        // 3. Tentativo di login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Login riuscito: resetta TUTTI i contatori
            Redis::del("login_attempts:{$identifier}");
            Redis::del("login_attempts_ip:{$ipOnlyIdentifier}");

            // Aggiorna timestamp ultimo cambio password
            $user = Auth::user();
            if (is_null($user->password_last_changed_at)) {
                $user->update(['password_last_changed_at' => now()]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // 4. Login fallito: aggiorna contatore email+IP
        $attempts = $attemptData ? json_decode($attemptData, true)['attempts'] ?? 0 : 0;
        $attempts++;

        $lockoutMinutes = 0;
        if ($attempts >= 5) {
            $lockoutMinutes = min(240, 5 * pow(2, $attempts - 5));
        }

        $data = [
            'attempts' => $attempts,
            'locked_until' => $lockoutMinutes > 0 ? time() + ($lockoutMinutes * 60) : null
        ];
        $ttl = $lockoutMinutes > 0 ? $lockoutMinutes * 60 : 3600;
        Redis::setex("login_attempts:{$identifier}", $ttl, json_encode($data));

        // 5. Login fallito: aggiorna contatore SOLO IP
        $ipAttempts = $ipAttemptsData ? json_decode($ipAttemptsData, true)['attempts'] ?? 0 : 0;
        $ipAttempts++;

        $ipLockoutMinutes = 0;
        if ($ipAttempts >= 20) {
            $ipLockoutMinutes = 60; // 1 ora
        }

        $ipData = [
            'attempts' => $ipAttempts,
            'locked_until' => $ipLockoutMinutes > 0 ? time() + ($ipLockoutMinutes * 60) : null
        ];
        $ipTtl = $ipLockoutMinutes > 0 ? $ipLockoutMinutes * 60 : 3600;
        Redis::setex("login_attempts_ip:{$ipOnlyIdentifier}", $ipTtl, json_encode($ipData));

        // 6. Messaggio di errore (priorità: blocco IP > blocco email+IP > errore generico)
        $errorMessage = 'Le credenziali fornite non sono corrette.';
        if ($ipLockoutMinutes > 0) {
            $errorMessage = "Troppe richieste dal tuo indirizzo. Riprova tra $ipLockoutMinutes minuti.";
        } elseif ($lockoutMinutes > 0) {
            $errorMessage = "Troppe richieste. Account bloccato per $lockoutMinutes minuti.";
        }

        return back()
            ->withErrors(['email' => $errorMessage])
            ->onlyInput('email');
    }

    /**
     * Mostra il form di registrazione.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Gestisce la registrazione di un nuovo utente.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'g-recaptcha-response' => ['required', new Recaptcha],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'password_last_changed_at' => now(), // Imposta timestamp iniziale
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Effettua il logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

        /**
     * Mostra il form per cambiare password.
     */
    public function showPasswordResetForm()
    {
        return view('auth.password-reset');
    }

    /**
     * Aggiorna la password dell'utente.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', 'min:8'],
        ], [
            'current_password.required' => 'La password attuale è obbligatoria.',
            'new_password.required' => 'La nuova password è obbligatoria.',
            'new_password.confirmed' => 'Le password non corrispondono.',
            'new_password.min' => 'La password deve contenere almeno 8 caratteri.',
        ]);

        // Verifica che la password attuale sia corretta
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'La password attuale non è corretta.'
            ]);
        }

        // Validazione personalizzata per i requisiti della password
        $newPassword = $validated['new_password'];
        $errors = [];

        if (!preg_match('/[A-Z]/', $newPassword)) {
            $errors['new_password'] = 'La password deve contenere almeno una lettera maiuscola.';
        }
        if (!preg_match('/[a-z]/', $newPassword)) {
            $errors['new_password'] = 'La password deve contenere almeno una lettera minuscola.';
        }
        if (!preg_match('/[0-9]/', $newPassword)) {
            $errors['new_password'] = 'La password deve contenere almeno un numero.';
        }
        if (!preg_match('/[^A-Za-z0-9]/', $newPassword)) {
            $errors['new_password'] = 'La password deve contenere almeno un simbolo.';
        }

        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        // Aggiorna password e timestamp
        $user->update([
            'password' => Hash::make($validated['new_password']),
            'password_last_changed_at' => now(),
        ]);

        // Logout automatico
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Password cambiata con successo! Accedi con la nuova password.');
    }
}
