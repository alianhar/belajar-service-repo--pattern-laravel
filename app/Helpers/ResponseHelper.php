<?php

namespace App\Helpers;

class ResponseHelper {

    public static function success($message = "success", array $data = [],$code = 200) {
        return response()->json([
            "status"=> 'success',
            "message"=> $message,
            'data' => $data
        ],$code);
    }

    public static function error($message = "error", array $errors = [], $code = 500) {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
