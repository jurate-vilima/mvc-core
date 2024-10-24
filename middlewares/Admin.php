<?php
namespace JurateVilima\MvcCore\middlewares;

use JurateVilima\MvcCore\Application;

class Admin extends BaseMiddleware {
    public function handle() {
        // if(!Application::$app->session->isGuest()) {
        //     redirect('location:/');
        //     exit;
        // }
        echo 'admin';
    }
}