<?php
namespace app\core\middlewares;

use app\core\Application;

class Patient extends BaseMiddleware {
    public function handle() {
        // if(!Application::$app->session->isGuest()) {
        //     redirect('location:/');
        //     exit;
        // }
        echo 'patient';
    }
}