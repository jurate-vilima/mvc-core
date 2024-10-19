<?php
namespace JurateVilima\MvcFramework;

class Response {
    public function setErrorCode(int $code) {
        http_response_code($code);
    }
}