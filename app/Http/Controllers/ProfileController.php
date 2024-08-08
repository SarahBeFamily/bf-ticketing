<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Notification;

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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the user's profile information.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's notifications.
     */
    public function notifications(): View
    {
        $notifications = Notification::where('user_id', auth()->id())->get();
        return view('profile.notifications', compact('notifications'));
    }

    /**
     * Delete all the user's notifications.
     */
    public function notificationDestroy(): RedirectResponse
    {
        Notification::where('user_id', auth()->id())->delete();
        return Redirect::route('profile.notifications')->with('success', 'Notifiche eliminate con successo.');
    }

    /**
     * Delete a single notification.
     * 
     * @param int $id
     */
    public function notificationDestroySingle(int $id): RedirectResponse
    {
        Notification::where('id', $id)->delete();
        return Redirect::route('profile.notifications')->with('success', 'Notifica eliminata con successo.');
    }
}
