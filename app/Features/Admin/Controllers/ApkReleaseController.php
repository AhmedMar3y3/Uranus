<?php

namespace App\Features\Admin\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Features\Admin\Services\ApkReleaseService;
use App\Features\Admin\Requests\StoreApkReleaseRequest;

class ApkReleaseController extends Controller
{
    public function store(StoreApkReleaseRequest $request, ApkReleaseService $releases): RedirectResponse
    {
        $releases->publish(
            $request->file('apk'),
            $request->safe()->only(['version', 'release_notes']),
            $request->user('admin')
        );

        return back()->with('status', 'The new APK is live and ready to download.');
    }
}
