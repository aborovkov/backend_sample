<?php

namespace Turing\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        switch (true) {

            case $exception instanceof ValidationException:
                return response()->json([
                    'success' => false,
                    'errors' => $exception->errors()
                ], 400);

            case $exception instanceof JWTException:
                return response()->json([
                    'success' => false,
                    'errors' => $exception->getMessage()
                ], 400);

            case $exception instanceof UnauthorizedHttpException:
                return response()->json(['success' => false], 401);

            case $exception instanceof NotFoundHttpException:
                return response()->json(['success' => false], 404);

            case $exception instanceof MethodNotAllowedHttpException:
                return response()->json(['success' => false, 'message' => $exception->getMessage()], 405);

            default:
                Log::error('Got exception', ['e' => $exception]);
                return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);

        }

    }
}
