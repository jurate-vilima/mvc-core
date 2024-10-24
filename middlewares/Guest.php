<?php
namespace JurateVilima\MvcCore\middlewares;

use JurateVilima\MvcCore\Application;

class Guest extends BaseMiddleware {
    public function handle() {
        if(!Application::$app->session->isGuest()) {
            throw new \Exception('You do not have permission to access this page.');
            // redirect('/');
        }
    }
}