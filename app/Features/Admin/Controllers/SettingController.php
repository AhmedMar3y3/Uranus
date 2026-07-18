<?php

namespace App\Features\Admin\Controllers;

use App\Features\Admin\Requests\UpdateSettingsRequest;
use App\Features\Settings\Services\AppVersionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function update(UpdateSettingsRequest $request, AppVersionService $versions): RedirectResponse
    {
        $data = $request->validated();
        $data['android_force_update'] = $request->boolean('android_force_update');

        $versions->updateSettings($data);

        return back()->with('status', 'Mobile app update settings saved.');
    }
}
