<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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
        
        // Save user data before deletion for notifications
        $userName = $user->name;
        $userEmail = $user->email;
        $userPhone = $user->phone;

        Auth::logout();

        $user->delete();

        // Notify admins that a user has deleted their account
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'title' => 'User Deleted Account',
                'message' => "User {$userName} ({$userEmail}) has deleted their account.",
                'type' => 'system_info',
                'is_read' => false,
            ]);
        }

        // Send a goodbye WhatsApp message to the user
        if ($userPhone) {
            try {
                $waMessage = "Halo Kak *{$userName}*,\n\n" .
                             "Akun Anda di Kana Covers telah berhasil dihapus. Kami sedih melihat Anda pergi 😢\n\n" .
                             "Terima kasih telah menggunakan layanan kami. Semoga kita bisa bertemu lagi di lain waktu!";
                \App\Services\FonnteService::send($userPhone, $waMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("WA Goodbye Failed for {$userPhone}: " . $e->getMessage());
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
