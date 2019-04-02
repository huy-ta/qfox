<?php

namespace App\Exceptions;

use App\Http\ApiErrorResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;

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
     * @param Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $exception->render();
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json(
                ApiErrorResponse::generate(
                    Response::HTTP_NOT_FOUND,
                    'We could not find the requested resource(s).',
                    [
                        'code' => $exception->getMessage(),
                        'model' => $exception->getModel(),
                        'id' => $exception->getIds()[0]
                    ]
                ), Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof QueryException) {
            if (strpos($exception->getMessage(), 'Integrity constraint violation: 1452')) {
                return response()->json(
                    ApiErrorResponse::generate(
                        Response::HTTP_NOT_FOUND,
                        $exception->getMessage(),
                        [
                            'code' => $exception->getCode(),
                            'sql' => $exception->getSql(),
                            'bindings' => $exception->getBindings()
                        ]
                    ), Response::HTTP_NOT_FOUND
                );
            }
        }

        return response()->json(
            ApiErrorResponse::generate(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Something went wrong on our side unexpectedly.',
                [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'type' => get_class($exception),
                    'file' => $exception->getFile(),
                    'trace' => $exception->getTraceAsString()
                ]
            ), Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
