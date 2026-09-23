<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organizer\RegisterOrganizerRequest;
use App\Models\Organizer;
use App\Models\Setting;
use App\Notifications\OrganizerRegistered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('organizer.auth.register');
    }

    public function store(RegisterOrganizerRequest $request): RedirectResponse
    {
        $requiresApproval = Setting::current()->require_organizer_approval;

        $organizer = Organizer::create([
            ...$request->validated(),
            'status' => $requiresApproval ? 'pending' : 'active',
        ]);

        $organizer->notify(new OrganizerRegistered($requiresApproval));

        if ($requiresApproval) {
            return redirect()->route('organizer.login')
                ->with('success', 'Thanks for registering! Your account is awaiting approval — we will email you once it is approved.');
        }

        Auth::guard('organizer')->login($organizer);
        $request->session()->regenerate();

        return redirect()->route('organizer.dashboard')->with('success', 'Welcome to Tiko! Your account is ready.');
    }
}
