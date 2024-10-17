<?php
namespace app\core;

class Response {
    public function setErrorCode(int $code) {
        http_response_code($code);
    }
}