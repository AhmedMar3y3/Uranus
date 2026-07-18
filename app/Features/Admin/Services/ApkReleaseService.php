<?php

namespace App\Features\Admin\Services;

use Throwable;
use App\Models\Admin;
use RuntimeException;
use App\Models\ApkRelease;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApkReleaseService
{
    public function publish(UploadedFile $apk, array $details, Admin $admin): ApkRelease
    {
        $path = $apk->storeAs('apks', Str::uuid() . '.apk', 'local');

        if (!is_string($path)) {
            throw new RuntimeException('The APK could not be stored.');
        }

        try {
            [$release, $previousPaths] = DB::transaction(function () use ($apk, $details, $admin, $path): array {
                $previousReleases = ApkRelease::query()->lockForUpdate()->get();
                $previousPaths    = $previousReleases->pluck('file_path')->filter()->all();
                ApkRelease::query()->delete();

                $release = ApkRelease::create([
                    'version'       => $details['version'] ?? null,
                    'release_notes' => $details['release_notes'] ?? null,
                    'file_path'     => $path,
                    'original_name' => $apk->getClientOriginalName(),
                    'mime_type'     => $apk->getClientMimeType(),
                    'file_size'     => $apk->getSize(),
                    'is_active'     => true,
                    'uploaded_by'   => $admin->id,
                ]);

                return [$release, $previousPaths];
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);

            throw $exception;
        }

        Storage::disk('local')->delete($previousPaths);

        return $release;
    }
}
