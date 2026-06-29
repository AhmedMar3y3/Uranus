<?php

namespace App\Features\Notifications\Services;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    public function sendToUser(User $user, string $title, string $body, array $data = []): void
    {
        $tokens = $user->devices()
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (! $tokens) {
            return;
        }

        $credentials = $this->credentialsPath();

        if (! $credentials || ! file_exists($credentials)) {
            Log::warning('Firebase credentials file was not found.', ['path' => $credentials]);
            return;
        }

        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($this->stringData($data + [
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ]));

            $report = (new Factory())
                ->withServiceAccount($credentials)
                ->createMessaging()
                ->sendMulticast($message, $tokens);

            $this->deleteInvalidTokens(array_merge($report->invalidTokens(), $report->unknownTokens()));
        } catch (\Throwable $exception) {
            Log::warning('Firebase notification failed.', [
                'user_id' => $user->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function credentialsPath(): ?string
    {
        $path = config('services.firebase.credentials');

        if (! $path) {
            return null;
        }

        return str_starts_with($path, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:\\\\/', $path)
            ? $path
            : base_path($path);
    }

    private function stringData(array $data): array
    {
        return collect($data)
            ->mapWithKeys(fn ($value, $key) => [(string) $key => is_scalar($value) || $value === null ? (string) $value : json_encode($value)])
            ->all();
    }

    private function deleteInvalidTokens(array $tokens): void
    {
        if ($tokens) {
            UserDevice::whereIn('fcm_token', $tokens)->delete();
        }
    }
}
