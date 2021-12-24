<?php

namespace App\Http\Library;

use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

trait ApiHelpers
{
    protected function esAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('admin');
        }

        return false;
    }

    protected function esAfiliado($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('afiliado');
        }

        return false;
    }

    protected function esVendedor($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('vendedor');
        }

        return false;
    }

    protected function esCoordinador($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('coordinador');
        }

        return false;
    }

    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function onError(int $code, string $message = '',$errors = ""): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    protected function onMessage(int $code, string $message = '', $data = ""): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

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

    protected function checkHeaders(string $contentType, $request)
    {
        if(!$request->expectsJson() or $contentType !== "application/json" ){
            return true;
        }

        return false;
    }

    protected function checkAcceptHeader($request){

        if(!$request->expectsJson()){
            return true;
        }

        return false;
    }

    protected function checkUuid($uuid){

        if(Str::isUuid($uuid)){

            return true;
        }

        return false;

    }

}