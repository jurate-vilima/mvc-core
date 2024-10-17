<?php
namespace app\core\exceptions;

use app\core\Application;

class ForbiddenAccess extends \Exception {
    public function __construct() {
        echo "You cannot get acces to this page";
    }
}