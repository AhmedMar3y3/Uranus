<?php

namespace App\Features\Downloads\Controllers;

use Illuminate\View\View;
use App\Models\ApkRelease;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Features\Downloads\Services\WebAnalyticsService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    public function __construct(private readonly WebAnalyticsService $analytics)
    {
    }

    public function show(Request $request): View
    {
        $this->analytics->recordVisit($request);
        $release    = $this->activeRelease();
        $legacyPath = config('apk.legacy_path');

        return view('download', [
            'release'      => $release,
            'apkAvailable' => $release !== null || (is_string($legacyPath) && is_file($legacyPath)),
            'apkSize'      => $release
                ? $this->formatBytes($release->file_size)
                : (is_string($legacyPath) && is_file($legacyPath) ? $this->formatBytes(filesize($legacyPath)) : null),
        ]);
    }

    public function download(Request $request): BinaryFileResponse|StreamedResponse
    {
        $release = $this->activeRelease();

        if ($release) {
            /** @var FilesystemAdapter $disk */
            $disk = Storage::disk('local');
            abort_unless($disk->exists($release->file_path), 404, 'The current APK file is unavailable.');

            $release->increment('downloads_count');
            $this->analytics->recordDownload($request, $release);

            return $disk->download($release->file_path, $release->original_name, [
                'Content-Type'  => 'application/vnd.android.package-archive',
                'Cache-Control' => 'no-store, private',
            ]);
        }

        $legacyPath = config('apk.legacy_path');
        abort_unless(is_string($legacyPath) && is_file($legacyPath), 404, 'No APK has been published yet.');
        $this->analytics->recordDownload($request);

        return response()->download($legacyPath, 'uranus.apk', [
            'Content-Type'  => 'application/vnd.android.package-archive',
            'Cache-Control' => 'no-store, private',
        ]);
    }

    private function activeRelease(): ?ApkRelease
    {
        return ApkRelease::query()->where('is_active', true)->orderByDesc('id')->first();
    }

    private function formatBytes(int $bytes): string
    {
        return number_format($bytes / 1024 / 1024, 1) . ' MB';
    }
}
