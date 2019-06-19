<?php
header('Content-Type: text/html; charset=utf-8');
spl_autoload_register(function (String $class) {
    $sourcePath = '/var/www/docs/lobochkin.ru/test/CRUD_oop/';
    $replaceDirectorySeparator = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $filePath = $sourcePath . $replaceDirectorySeparator . '.php';
    if (file_exists($filePath)) {
        require($filePath);
    }
});

$server = "localhost"; 
$username = "root";  
$password = "4lHQQd57pZMU"; 
$database = "test";
