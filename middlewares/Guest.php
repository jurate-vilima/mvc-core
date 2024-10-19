<?php
namespace JurateVilima\MvcFramework\middlewares;

use JurateVilima\MvcFramework\Application;

class Guest extends BaseMiddleware {
    public function handle() {
        if(!Application::$app->session->isGuest()) {
            throw new \Exception('You do not have permission to access this page.');
            // redirect('/');
        }
    }
}