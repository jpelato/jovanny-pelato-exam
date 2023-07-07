<?php
declare(strict_types=1);
require __DIR__ . '/req_header.php';
require __DIR__ . '/db_connect.php';
if($_SERVER['REQUEST_METHOD']  === 'POST'){
    $json_data =  json_decode(file_get_contents("php://input"), TRUE);
    save('youtube_channels',$json_data);
}
if($_SERVER['REQUEST_METHOD']  === 'GET'){
    getAll('youtube_channels');
}