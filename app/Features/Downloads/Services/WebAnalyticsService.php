<?php

namespace App\Features\Downloads\Services;

use App\Models\WebEvent;
use App\Models\ApkRelease;
use Illuminate\Http\Request;

class WebAnalyticsService
{
    public function recordVisit(Request $request): void
    {
        $this->record(WebEvent::TYPE_VISIT, $request);
    }

    public function recordDownload(Request $request, ?ApkRelease $release = null): void
    {
        $this->record(WebEvent::TYPE_DOWNLOAD, $request, $release);
    }

    private function record(string $type, Request $request, ?ApkRelease $release = null): void
    {
        WebEvent::create([
            'type'           => $type,
            'visitor_hash'   => hash_hmac(
                'sha256',
                ($request->ip() ?? 'unknown') . '|' . ($request->userAgent() ?? 'unknown'),
                (string) config('app.key')
            ),
            'apk_release_id' => $release?->id,
            'occurred_at'    => now(),
        ]);
    }
}
