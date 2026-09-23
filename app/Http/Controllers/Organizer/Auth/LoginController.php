<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Http\Concerns\ThrottlesLogins;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function show(): View
    {
        return view('organizer.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited($request);

        if (! Auth::guard('organizer')->attempt($credentials, $request->boolean('remember'))) {
            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $organizer = Auth::guard('organizer')->user();

        if (! $organizer->isActive()) {
            Auth::guard('organizer')->logout();

            $message = $organizer->status === 'pending'
                ? 'Your account is awaiting approval. We will email you as soon as it is approved.'
                : 'Your organizer account has been suspended. Please contact Tiko support.';

            throw ValidationException::withMessages(['email' => $message]);
        }

        $this->clearLoginAttempts($request);
        $request->session()->regenerate();

        return redirect()->intended(route('organizer.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('organizer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('organizer.login');
    }
}
