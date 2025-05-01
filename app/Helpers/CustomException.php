<?php

namespace App\Helpers;

use Exception;

class CustomException extends Exception {

    protected $statusCode;

    public function __construct($message = "error", $statusCode = 400) {

        parent::__construct($message, $statusCode);

        $this->statusCode = $statusCode;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }
}