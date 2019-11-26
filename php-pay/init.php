<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/17
 * Time: 0:36
 */
ini_set('date.timezone','Asia/Shanghai');
header("Content-Type: text/html;charset=utf-8");
//自动加载
spl_autoload_register("autoload");
function autoload($class_name){

    if(file_exists("../service/$class_name".".php")){
        include_once "../service/$class_name".".php";
    }else if(file_exists("../controller/$class_name".".php")){
        include_once "../controller/$class_name".".php";
    }
}

$db = Db::getInstance('db_system');
//支付回调域名
$sql = "select *  from pt_dict_config where DICT_GROUP='php'";
$res = $db->getRows($sql);
$php_pay_url = '';
$php_client_url = '';
foreach($res as $k=>$v){
    if($v['DICT_GROUP'] == 'php' && $v['TAG'] == 'php_pay_url'){
        $php_pay_url = $v['VAL'];
    }else if($v['DICT_GROUP'] == 'php' && $v['TAG'] == 'php_client_url'){
        $php_client_url = $v['VAL'];
    }
}

if($php_pay_url == '' || $php_client_url==''){
    echo json_encode(['state'=>-1,'msg'=>'system error code:11']);exit;
}

$php_pay_url = trim($php_pay_url);
if(substr($php_pay_url, -1) == '/'){
    $php_pay_url = substr($php_pay_url,0,strlen($php_pay_url)-1);
}
define('PHP_PAY_URL',$php_pay_url);

$php_client_url = trim($php_client_url);
if(substr($php_client_url, -1) == '/'){
    $php_client_url = substr($php_client_url,0,strlen($php_client_url)-1);
}
define('PHP_CLIENT_URL',$php_client_url);



