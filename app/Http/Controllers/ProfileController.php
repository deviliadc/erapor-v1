<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        //
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $breadcrumbs = [
            ['label' => 'Edit Profile', 'url' => route('profile.edit')],
        ];
        $title = 'Edit Profile';

        return view('profile.edit', [
            'user' => $user,
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
            'page' => 'Profile',
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:25'],
        ]);

        $user->email = $validated['email'];

        // Simpan nomor HP ke siswa atau guru
        if (!empty($validated['phone'])) {
            if ($user->siswa) {
                $user->siswa->phone = $validated['phone'];
                $user->siswa->save();
            } elseif ($user->guru) {
                $user->guru->phone = $validated['phone'];
                $user->guru->save();
            }
        }

        // Reset email verification jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
