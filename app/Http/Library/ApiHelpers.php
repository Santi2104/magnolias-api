<?php

namespace App\Http\Library;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;


trait ApiHelpers
{
    /**
     * @param Object $user
     * @return bool
     */
    protected function esAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('admin');
        }

        return false;
    }

    /**
     * @param Object $user
     * @return bool
     */
    protected function esAfiliado($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('afiliado');
        }

        return false;
    }
    
    /**
     * @param Object $user
     * @return bool
     */
    protected function esVendedor($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('vendedor');
        }

        return false;
    }

    /**
     * @param Object $user
     * @return bool
     */
    protected function esCoordinador($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('coordinador');
        }

        return false;
    }

    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param int $code
     * @param string $message
     * @param string $errors
     * @return JsonResponse
     */
    protected function onError(int $code, string $message = '',$errors = ""): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * @param int $code
     * @param string $message
     * @param string $data
     * @return JsonResponse
     */
    protected function onMessage(int $code, string $message = '', $data = ""): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * @param string $accesToken
     * @param string $expiresAt
     * @param string $message
     * @param $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function loginResponse(string $accesToken, string $expiresAt,string $message = "" ,$data, int $code = 200)
    {
        return response()->json([
            'access_token' => $accesToken,
            'token_type'   => 'Bearer',
            'expires_at'   => $expiresAt,
            'user'         => $data,
            'code'         => $code,
            'message'      => $message 
        ]);     
    }

    /**
     * @param string $contentType
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    protected function checkHeaders(string $contentType, $request)
    {
        if(!$request->expectsJson() or $contentType !== "application/json" ){
            return true;
        }

        return false;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    protected function checkAcceptHeader($request){

        if(!$request->expectsJson()){
            return true;
        }

        return false;
    }

    /**
     * @param string $uuid
     * @return boolean
     */
    protected function checkUuid($uuid){

        if(Str::isUuid($uuid)){

            return true;
        }

        return false;

    }

    /**
     * @param \Illuminate\Http\Request $fechaNacimiento
     * @return $edad
     */
    protected function calcularEdad($fechaNacimiento)
    {
        $nacimiento = Carbon::parse($fechaNacimiento)->format('Y-m-d');
        $edad = Carbon::now()->diffInYears($nacimiento);
        return $edad;
    }

    /**
     * @param  $fecha
     * @return boolean
     */
    protected function puedeEditar($fecha)
    {
        $fechaCreacion = Carbon::parse($fecha);
        $diferencia = $fechaCreacion->diffIndays(now());

        if($diferencia != 0)
        {
            return false;
        }

        return true;
    }

}