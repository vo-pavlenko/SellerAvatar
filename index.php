<?
	
error_reporting(0);
ini_set('display_errors', 0);

require_once(__DIR__."/db/Db.php");
require_once(__DIR__."/router/Router.php");

$main_page_handler = function ($data){
    $title = 'ТЗ - SellerAvatar';
    $description = 'Реализация тестового задания Павленко В.А.';

    $data = Db::get_data();

    require('view/view_home.php');
};

$router = new Router();

$router->get("/", $main_page_handler);

$router->start();












