<?php
namespace JurateVilima\MvcCore\middlewares;

use JurateVilima\MvcCore\Application;

class Patient extends BaseMiddleware {
    public function handle() {
        // if(!Application::$app->session->isGuest()) {
        //     redirect('location:/');
        //     exit;
        // }
        echo 'patient';
    }
}