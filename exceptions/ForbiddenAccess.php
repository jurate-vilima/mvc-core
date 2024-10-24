<?php
namespace JurateVilima\MvcCore\exceptions;

use JurateVilima\MvcCore\Application;

class ForbiddenAccess extends \Exception {
    public function __construct() {
        echo "You cannot get acces to this page";
    }
}