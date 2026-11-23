<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilSayaController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $user->load(['warga.kartuKeluarga.wargas']);

        $warga = $user->warga;
        $kartuKeluarga = $warga?->kartuKeluarga;
        $anggotaKeluarga = $kartuKeluarga?->wargas ?? collect();

        return view('admin.profil.saya', compact('user', 'warga', 'kartuKeluarga', 'anggotaKeluarga'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $warga = $user->warga;

        $request->validate([
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password_lama' => ['nullable', 'required_with:password_baru', 'current_password'],
            'password_baru' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'avatar.image' => 'Berkas harus berupa gambar foto.',
            'avatar.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh pengguna lain.',
            'password_lama.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password_baru.min' => 'Kata sandi baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            if ($avatarFile && $avatarFile->isValid()) {
                $uploadDir = public_path('uploads/avatars');
                if (! File::isDirectory($uploadDir)) {
                    File::makeDirectory($uploadDir, 0755, true, true);
                }

                if ($user->avatar && File::exists(public_path($user->avatar))) {
                    @File::delete(public_path($user->avatar));
                }

                $filename = 'avatar_admin_' . $user->id . '_' . time() . '.' . $avatarFile->getClientOriginalExtension();
                $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                if (! @copy($avatarFile->getRealPath(), $targetPath)) {
                    $avatarFile->move($uploadDir, $filename);
                }
                $user->avatar = 'uploads/avatars/' . $filename;
            }
        } elseif ($request->boolean('hapus_avatar')) {
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                @File::delete(public_path($user->avatar));
            }
            $user->avatar = null;
        }

        $user->email = $request->email;

        if ($request->filled('password_baru')) {
            $user->password = Hash::make($request->password_baru);
        }

        $user->save();

        if ($warga && $request->has('no_hp')) {
            $warga->update([
                'no_hp' => $request->input('no_hp'),
            ]);
        }

        return redirect()
            ->route('admin.profil.saya')
            ->with('sukses', 'Profil administrator berhasil diperbarui.');
    }
}
