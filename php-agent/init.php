<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/17
 * Time: 0:36
 */
ini_set('date.timezone','Asia/Shanghai');
header("Content-Type: text/html;charset=utf-8");
header("Access-Control-Allow-Origin: *");
//自动加载
spl_autoload_register("autoload");
function autoload($class_name){
    if(file_exists("../service/$class_name".".php")){
        include_once "../service/$class_name".".php";
    }else if(file_exists("../controller/$class_name".".php")){
        include_once "../controller/$class_name".".php";
    }else if(file_exists("../model/$class_name".".php")){
        include_once "../model/$class_name".".php";
    }
}
define('DB_AGENT',config('db_agent'));
