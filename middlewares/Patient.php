<?php
namespace JurateVilima\MvcFramework\middlewares;

use JurateVilima\MvcFramework\Application;

class Patient extends BaseMiddleware {
    public function handle() {
        // if(!Application::$app->session->isGuest()) {
        //     redirect('location:/');
        //     exit;
        // }
        echo 'patient';
    }
}