<?php
declare(strict_types=1);
require __DIR__ . '/req_header.php';
require __DIR__ . '/db_connect.php';
if($_SERVER['REQUEST_METHOD']  === 'POST'){
    $json_data =  json_decode(file_get_contents("php://input"), TRUE);
    delete('youtube_channel_videos','channelId = \''.$json_data[0]['channelId'].'\'');
    saveMultiple('youtube_channel_videos',$json_data);
    getBy('youtube_channel_videos',"channelId = '".$json_data[0]['channelId']."' ORDER BY videoPublishedAt");
}
