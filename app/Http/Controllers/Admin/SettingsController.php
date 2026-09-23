<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => Setting::current()]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        Setting::current()->update([
            ...$request->validated(),
            'require_organizer_approval' => $request->boolean('require_organizer_approval'),
        ]);

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated.');
    }
}
