<?php
namespace JurateVilima\MvcCore\middlewares;

 class BaseMiddleware {
    public const MAP = [
        'admin' => Admin::class,
        'patient' => Patient::class,
        'guest' => Guest::class,
    ];
    // public  function handle();
}