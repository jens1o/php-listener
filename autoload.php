<?php

spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);
    if($parts[0] === 'jens1o') {
        // Our beer
        array_shift($parts);
        $path = 'lib/' . implode('/', $parts) . '.class.php';
        if(is_readable($path)) {
            require_once $path;
            return true;
        }
    }
    return false;
});
