<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Psr\Log\LogLevel;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e) {
            if($e instanceof ValidationException){
                return response([
                    'status_code' => 422,
                    'message'     => $e->getMessage(),
                    'data'        => [],
                    'error'       => true
                ], 422);
            }

            $status_code = 400;
            if(method_exists($e, 'getStatusCode')){
                $status_code = $e->getStatusCode();
            }

            $message = 'Server Error';
            if ($status_code === 404) {
                $message = 'Not Found';
            }
            return response([
                'status_code' => $status_code,
                'message'     => $message,
                'data'        => [],
                'error'       => true
            ], $status_code);
        });
    }
}
