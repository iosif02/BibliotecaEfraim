<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CustomException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(["error" => true, "message" => $this->getMessage()], 400);
    }
}
