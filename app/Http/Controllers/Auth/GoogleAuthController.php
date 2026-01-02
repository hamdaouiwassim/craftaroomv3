<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }

                // Update photo if available and not set
                if (!$user->photoUrl && $googleUser->getAvatar()) {
                    $user->photoUrl = $googleUser->getAvatar();
                    $user->save();
                }
            } else {
                // Create new user
                // Default to customer role (2) for Google sign-ups
                // Users can change their role later if needed
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'photoUrl' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(16)), // Random password since OAuth
                    'email_verified_at' => now(), // Google emails are verified
                    'loginType' => 2, // Google login type
                    'role' => 2, // Default to customer role
                ]);
            }

            // Log the user in
            Auth::login($user, true);

            return redirect()->intended(route('dashboard', absolute: false));

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again.');
        }
    }
}

