<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return response()->json([404], 404);
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
           return response()->json([404], 404);
        }

        if ($exception instanceof \Illuminate\Database\QueryException) {
//            dd($exception);
            switch ($exception->errorInfo[0]) {
                case 23502:
                    return response()->json(["error"=>'Поля не должны быть пустыми'],503);
                    break;
                case 23503:
                    return response()->json(["error"=>'Нарушена целостность данных'],503);
                    break;
            }
        }
        return parent::render($request, $exception);
    
    }
}