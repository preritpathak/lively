<?php

$http = new swoole_http_server("0.0.0.0", 8181);
$http->on('request', function ($request, $response) {
    $response->header("Content-Type", "text/html; charset=utf-8");
    $response->header('Access-Control-Allow-Origin', '*');
    $response->header('Access-Control-Allow-Credentials', 'true');
    $response->header('Access-Control-Allow-Methods', 'GET, POST,OPTIONS');
    $jsonData=json_encode($request);
    $postDataArray=json_decode($jsonData,true);
    $streamData=array();
    foreach($postDataArray['post']['data'] as $url){
        if(strpos($url,"rtmp://") == 0){
                $streamData[]=$url;
        }
    }
        $nginxconf='worker_processes  1;events{worker_connections  1024;}rtmp {server{listen 1935;application live {live on;exec /app/bin/ffmpeg -i rtmp://localhost:1935/live/$name -c:v libx264 -c:a libfdk_aac -preset:v medium  -b_strategy 0 -force_key_frames "expr:gte(t,n_forced*1)"  -vf "scale=240:426" -b:v 400k -b:a 96k -tune zerolatency -threads 0 -f flv rtmp://localhost/mid/$name -c:v libx264 -c:a libfdk_aac -preset:v medium  -b_strategy 0 -force_key_frames "expr:gte(t,n_forced*1)"  -vf "scale=240:426" -b:v 250k -b:a 48k -tune zerolatency -threads 0 -f flv rtmp://localhost/low/$name -c:v libx264 -c:a libfdk_aac -preset:v medium  -b_strategy 0 -force_key_frames "expr:gte(t,n_forced*1)"  -vf "scale=240:426" -b:v 250k -b:a 48k -tune zerolatency -threads 0 -f flv rtmp://localhost/dash/$name';
        foreach($streamData as $streams){
                $nginxconf.=" -c:v libx264 -c:a libfdk_aac  -f flv {$streams}";
        }
	$nginxconf.=";}application mid{live on;hls on;record off;hls_nested on;hls_path /memstore/hls/mid;hls_fragment 1s;hls_playlist_length 20s;hls_fragment_naming timestamp;hls_fragment_slicing aligned;hls_cleanup on;hls_continuous on;hls_variant _mid BANDWIDTH=64000,RESOLUTION=256x256;}application low{live on;hls on;record off;hls_nested on;hls_path /memstore/hls/low;hls_fragment 1s;hls_playlist_length 20s;hls_fragment_naming timestamp;hls_fragment_slicing aligned;hls_cleanup on;hls_continuous on;hls_variant _low BANDWIDTH=34000,RESOLUTION=144x144;}application dash{live on;dash on;record off;dash_path /memstore/dash;dash_fragment 1s;dash_playlist_length 5s;dash_cleanup on;}}}http{sendfile off;tcp_nopush on;directio 512;default_type application/octet-stream;access_log /tmp/httpaccess.log;error_log /tmp/httperror.log;server {listen 8080;location / {add_header 'Cache-Control' 'no-cache';add_header 'Access-Control-Allow-Origin' '*' always;add_header 'Access-Control-Expose-Headers' 'Content-Length';types {application/dash+xml mpd;application/vnd.apple.mpegurl m3u8;video/mp2t ts;text/html html;}root /memstore/;}}}";
	if(!empty($streamData)){
                file_put_contents("/app/nginx.conf",$nginxconf);
        }
        shell_exec("/usr/local/nginx/sbin/nginx -s stop");
        shell_exec("/usr/local/nginx/sbin/nginx -c /app/nginx.conf");
 $response->end(json_encode($result));
});
$http->start();

