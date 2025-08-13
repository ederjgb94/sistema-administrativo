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
        \Illuminate\Support\Facades\Log::info('=== PROFILE UPDATE INICIO ===', [
            'user_id' => $request->user()->id,
            'current_name' => $request->user()->name,
            'request_data' => $request->all(),
            'validated_data' => $request->validated()
        ]);

        $user = $request->user();
        $validatedData = $request->validated();

        \Illuminate\Support\Facades\Log::info('Datos validados', $validatedData);

        $user->fill($validatedData);

        \Illuminate\Support\Facades\Log::info('Usuario después de fill', [
            'name' => $user->name,
            'email' => $user->email,
            'isDirty' => $user->isDirty(),
            'dirtyAttributes' => $user->getDirty()
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $result = $user->save();

        \Illuminate\Support\Facades\Log::info('Resultado del save', [
            'save_result' => $result,
            'user_name_after_save' => $user->fresh()->name
        ]);

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
