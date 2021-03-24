<?php

function json_response($code = 200, $result = []){
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        500 => '500 Internal Server Error'
    );

    header_remove();
    http_response_code($code);
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    header('Content-Type: application/json');
    header('Status: '.$status[$code]);

    return json_encode(array(
        'status' => $code < 300,
        'result' => $result
    ));
}