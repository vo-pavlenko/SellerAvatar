<?php

require_once('../db/Db.php');
require_once('../tools/json_response.php');

$id = $_GET['id'];
$text = $_GET['text'];
$sum = $_GET['sum'];

$oldText = Db::get_data_text_by_id($id);

$newText = $oldText['text'].$text;

$data = ['text' => $newText, 'sum' => $sum];
if ($text != '' && ($sum != 0 || $sum != '')) {
    $result = Db::update_data_by_id($id, $data);
}

if($result){
    echo json_response(200, ["message" => "Ok"]);
}
else{
    echo json_response(400, ["message" => "Something wrong"]);
}