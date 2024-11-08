<?php

namespace App\Classes;

use Illuminate\Http\JsonResponse;

class ApiClass
{
  public static function success(mixed $data): JsonResponse
  {
    return response()->json([
      'status_code'=>200,
      'message'=>'success',
      'data'=>$data
    ],200);
  }

  public static function error(string $message): JsonResponse
  {
    return response()->json([
      'status_code'=>500,
      'message'=>$message,
    ],500);
  }
  
  public static function unauthorized(): JsonResponse
  {
    return response()->json([
      'status_code'=>401,
      'message'=>'Unauthorized access',
    ],401);
  }
}