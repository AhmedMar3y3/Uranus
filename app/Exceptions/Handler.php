<?php

namespace App\Exceptions;

use Throwable;
use Carbon\Carbon;
use App\Enums\StatusCodesEnum;
use App\Http\Controllers\ApiController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
  /**
   * The list of the inputs that are never flashed to the session on validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation',
  ];

  /**
   * Register the exception handling callbacks for the application.
   */
  public function register(): void
  {
    $this->reportable(function (Throwable $e) {
      //
    });
  }


  public function render($request, Throwable $exception)
  {
    if ($request->is('api/*')) {
      $apiController = new ApiController();
      
      if ($exception instanceof ModelNotFoundException) {
        return $apiController->notFoundResponse(__('messages.not_found'));
      }

      if ($exception instanceof NotFoundHttpException) {
        return $apiController->notFoundResponse(__('messages.not_found'));
      }

      if ($exception instanceof AuthenticationException) {
        return $apiController->unauthenticatedResponse();
      }

      if ($exception instanceof ValidationException) {
        return $apiController->resetResponse()
          ->setError(StatusCodesEnum::VALIDATION_ERROR->value, $exception->errors() ? collect($exception->errors())->flatten()->first() : __('messages.invalid_data'))
          ->addResponseField('errors', $exception->errors())
          ->setHttpCode(422)
          ->response();
      }

      if ($exception instanceof ThrottleRequestsException) {
        $retryAfter = (Carbon::now()->diffInMinutes(Carbon::parse($exception->getHeaders()['X-RateLimit-Reset']))) ?? 30;
        return $apiController->resetResponse()
          ->setError(StatusCodesEnum::THROTTLE_ERROR->value, __('auth.throttle', ['minutes' => $retryAfter]))
          ->setHttpCode(429)
          ->response();
      }

      $message = $exception->getMessage();
      $debugData = [];
      
      if (config('app.debug')) {
        $debugData = [
          'line' => $exception->getLine(),
          'file' => $exception->getFile(),
          'trace' => $exception->getTraceAsString(),
        ];
      }

      return $apiController->resetResponse()
        ->setError(StatusCodesEnum::SERVER_ERROR->value, $message)
        ->addResponseField('error_details', $debugData, config('app.debug'))
        ->setHttpCode(500)
        ->response();
    }

    if ($exception instanceof NotFoundHttpException && !$request->is('api/*')) {
      return response()->view('404', [], 404);
    }

    return parent::render($request, $exception);
  }
}
