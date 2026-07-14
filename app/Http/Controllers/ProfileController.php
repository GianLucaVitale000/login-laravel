<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Mostra la pagina del profilo utente.
     */
    public function show()
    {
        return view('profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Aggiorna il profilo utente (nome, email, avatar).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Aggiorna nome e email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Gestione avatar
        if ($request->hasFile('avatar')) {
            // Crea directory se non esiste (con permessi corretti)
            $avatarPath = storage_path('app/public/avatars');
            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0775, true);
            }

            // Elimina vecchio avatar se esiste
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            $file = $request->file('avatar');
            $filename = 'avatars/' . time() . '_' . $file->getClientOriginalName();
            $sourcePath = $file->getRealPath();
            $targetPath = storage_path('app/public/' . $filename);

            // Crea immagine da file (supporta JPG, PNG, GIF)
            $source = @imagecreatefromjpeg($sourcePath) ?: 
                      @imagecreatefrompng($sourcePath) ?: 
                      @imagecreatefromgif($sourcePath);

            if ($source !== false) {
                $width = imagesx($source);
                $height = imagesy($source);

                // Calcola nuove dimensioni (max 128x128, mantiene aspect ratio)
                $ratio = min(128 / $width, 128 / $height);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);

                // Crea nuova immagine con trasparenza (per PNG)
                $newImage = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);

                imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                // Salva in base al tipo originale
                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext === 'jpg' || $ext === 'jpeg') {
                    imagejpeg($newImage, $targetPath, 90);
                } elseif ($ext === 'png') {
                    imagepng($newImage, $targetPath, 9);
                } elseif ($ext === 'gif') {
                    imagegif($newImage, $targetPath);
                }

                imagedestroy($source);
                imagedestroy($newImage);
            } else {
                // Fallback: copia file originale se non si può processare
                $file->storeAs('public/avatars', basename($filename));
            }

            $user->avatar = $filename;
        }

        $user->save();

        return back()->with('success', 'Profilo aggiornato con successo!');
    }
}
