<?php

namespace App\Features\Auth\Jobs;

use App\Features\Auth\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class SendOtpMailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    public function __construct(
        private readonly int $userId,
        private readonly string $plainOtp,
    ) {
    }

    public function backoff(): array
    {
        return [10, 30, 60, 120, 300];
    }

    public function handle(Mailer $mailer): void
    {
        $user = User::query()->find($this->userId);

        if (!$user) {
            $this->fail(new RuntimeException('Unable to send OTP email because the user no longer exists.'));

            return;
        }

        try {
            $mailer->to($user->email)->send(new LoginOtpMail($this->plainOtp));

            Log::info('OTP email delivered successfully.', $this->logContext($user));
        } catch (TransportExceptionInterface $exception) {
            Log::warning('OTP email transport failure; the queue worker will retry the job.', $this->logContext($user, $exception));

            throw $exception;
        } catch (Throwable $exception) {
            $this->fail($exception);
        }
    }

    public function failed(?Throwable $exception): void
    {
        $user = User::query()->find($this->userId);

        Log::error('OTP email delivery failed.', $this->logContext($user, $exception));
    }

    private function logContext(?User $user = null, ?Throwable $exception = null): array
    {
        return array_filter([
            'user_id'           => $this->userId,
            'email'             => $user?->email,
            'job_id'            => $this->jobId(),
            'attempt'           => $this->attempts(),
            'max_tries'         => $this->tries,
            'exception_message' => $exception?->getMessage(),
        ], static fn($value): bool => $value !== null);
    }

    private function jobId(): ?string
    {
        $jobId = $this->job?->getJobId();

        return $jobId === null ? null : (string) $jobId;
    }
}
