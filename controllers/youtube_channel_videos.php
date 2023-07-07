<?php
declare(strict_types=1);
require __DIR__ . '/req_header.php';
require __DIR__ . '/db_connect.php';
if($_SERVER['REQUEST_METHOD']  === 'POST'){
    
}
if($_SERVER['REQUEST_METHOD']  === 'GET'){
    echo 'get';
}