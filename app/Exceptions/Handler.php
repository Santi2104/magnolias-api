<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
//use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function(AccessDeniedHttpException $e , $request){
            
            return response()->json([
                'status' => false,
                'code' => $e->getStatusCode(),
                'message' => 'No tiene los permisos necesarios para acceder a este recurso',
                'errors' => null,
            ],Response::HTTP_FORBIDDEN);
        });

        $this->renderable( function(NotFoundHttpException $e, $request){

            return response()->json([
                'status' => false,
                'code' => $e->getStatusCode(),
                'message' => "El recurso al que se quiere acceder no existe",
                'errors' => $e
            ], Response::HTTP_NOT_FOUND);
        } );

        $this->renderable( function(RouteNotFoundException $e, $request){

            if($request->is('api/*')){
                return response()->json([
                    'status' => false,
                    'code' => 422,
                    'message' => "El servidor no recibio los datos necesarios para procesar la operación",
                    'errors' => null
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

        } );
    }
}
