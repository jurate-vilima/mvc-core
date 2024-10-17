<?php
namespace app\core\middlewares;

use app\core\Application;

class Admin extends BaseMiddleware {
    public function handle() {
        // if(!Application::$app->session->isGuest()) {
        //     redirect('location:/');
        //     exit;
        // }
        echo 'admin';
    }
}