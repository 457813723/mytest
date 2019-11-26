<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/12
 * Time: 14:27
 */
require "../lib.php";
require "../init.php";
$p = $_GET['pp'];//三方平台
$m = explode('.',$_GET['mm'])[0];//回调方式 异步或者同步
$p::callback($m);
