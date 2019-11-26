<?php
//use controller\plate\myteahouse;
//header("Access-Control-Allow-Origin:*");
require "../lib.php";
require "../init.php";
require "../route/api.php";

$_POST = file_get_contents("php://input");
$_POST = json_decode($_POST,true);
$_GET = addslashes_deep($_GET);
$_POST = addslashes_deep($_POST);
$c = $_GET['p'];
$m = explode('.',$_GET['m'])[0];

$cm = route::decode($c.'/'.$m);

$c = $cm[0];
$m = $cm[1];
$controller = new $c();
$controller->$m();

