<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'status_code' => $code,
            'data'    => $result,
            'message' => $message
        ];

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $message, $code = 401)
    {
        $response = [
            'success' => false,
            'status_code' => $code,
            'data' => $error,
            'message' => $message
        ];

        return response()->json($response, $code);
    }
}
