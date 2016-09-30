<?php

spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);
    var_dump($parts);
    if($parts[0] === 'jens1o') {
        // Our beer
        array_shift($parts);
        $path = 'lib/' . implode('/', $parts) . '.class.php';
        echo PHP_EOL . $path . PHP_EOL;
        if(is_readable($path)) {
            require_once $path;
            return true;
        }
    }
    return false;
});
