<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    // GANTI FUNGSI UPDATE MENJADI INI
    public function update(\Illuminate\Http\Request $request)
    {
        // 1. Validasi Nama & Email
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255', 
                \Illuminate\Validation\Rule::unique('users')->ignore($request->user()->id)
            ],
        ]);

        $user = $request->user();
        $user->fill($request->only('name', 'email'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 2. Logika Tambahan: Validasi & Simpan Kata Sandi (Hanya jika diisi)
        if ($request->filled('current_password') || $request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ], [
                'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
                'password.confirmed' => 'Konfirmasi sandi baru tidak sama.'
            ]);
            
            // Enkripsi dan timpa sandi lama
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        // 3. Simpan seluruh data ke database
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }
}
