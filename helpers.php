<?php
use JurateVilima\MvcCore\Application;

function getPageTitles() {
    return [
        'login' => 'Ielogoties sistēmā', 
        'register' => 'Reģistrācija',
        'contacts' => 'Kontakti',
        'home' => 'Galvenā lapa',
        'profile' => 'Profils',
    ];
} 

// creates a condition with placeholders
// name = :name AND email = :email
function buildAndCondition($params) {
    $params = conditionPlaceholders($params);
    $paramString = implode(" AND ", $params); 
    return $paramString;
}

function createKeyValueArray($object, $keys) {
    $associativeArr = [];

    foreach($keys as $key) {
        $associativeArr[$key] = $object->$key;
    }

    return $associativeArr;
}

// Converts an array of keys to a comma-separated string (e.g. username,password)
function implodeArray($array) {
    return implode(',', $array);
}

function prependColonToArrayValues($array) {
    return array_map(fn($value) => ":$value", $array);
}

// creates an array with placeholders
// firstname = :firstname
function conditionPlaceholders($array) {
    $arr = array_map(function($key) {
        return "$key = :$key";
    }, array_keys($array));

    return $arr;
}

function redirect($url) {
    header("Location: " . Application::$app::$BASE_URL . $url);
    exit();
}

//get name of a method which called current method
function getCallingFunction() {
    $backtrace = debug_backtrace();
    var_dump($backtrace);
    // if (isset($backtrace[1])) {
    //     return $backtrace[1]['function'];
    // } 
}