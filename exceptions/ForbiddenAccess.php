<?php
namespace JurateVilima\MvcFramework\exceptions;

use JurateVilima\MvcFramework\Application;

class ForbiddenAccess extends \Exception {
    public function __construct() {
        echo "You cannot get acces to this page";
    }
}