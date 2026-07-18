<?php

namespace App\Features\Settings\Services;

use App\Models\ApkRelease;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class AppVersionService
{
    public const DEFAULT_UPDATE_MESSAGE = 'A new version of Uranus is available. Update now to continue.';

    public function settings(): array
    {
        $settings = Setting::query()
            ->whereIn('key', [
                'android_latest_version',
                'android_latest_version_code',
                'android_force_update',
                'android_update_message',
            ])
            ->pluck('value', 'key');

        return [
            'android_latest_version'      => (string) $settings->get('android_latest_version', ''),
            'android_latest_version_code' => (int) $settings->get('android_latest_version_code', 0),
            'android_force_update'        => filter_var(
                $settings->get('android_force_update', true),
                FILTER_VALIDATE_BOOLEAN
            ),
            'android_update_message'      => (string) $settings->get(
                'android_update_message',
                self::DEFAULT_UPDATE_MESSAGE
            ),
        ];
    }

    public function updateSettings(array $data): void
    {
        DB::transaction(function () use ($data): void {
            foreach ($data as $key => $value) {
                Setting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
                );
            }
        });
    }

    public function check(int $currentVersionCode, ?string $currentVersion = null): array
    {
        $settings = $this->settings();
        $latestCode = $settings['android_latest_version_code'];
        $updateRequired = $latestCode > 0 && $currentVersionCode < $latestCode;
        $release = ApkRelease::query()->where('is_active', true)->orderByDesc('id')->first();

        return [
            'platform'            => 'android',
            'current_version'     => $currentVersion,
            'current_version_code'=> $currentVersionCode,
            'latest_version'      => $settings['android_latest_version'],
            'latest_version_code' => $latestCode,
            'update_required'     => $updateRequired,
            'must_update'         => $updateRequired && $settings['android_force_update'],
            'force_update'        => $settings['android_force_update'],
            'update_message'      => $settings['android_update_message'],
            'download_url'        => route('apk.download'),
            'apk_available'       => $release !== null || is_file((string) config('apk.legacy_path')),
        ];
    }
}
