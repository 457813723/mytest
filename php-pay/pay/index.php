<?php
require "../lib.php";
require "../init.php";
$p = $_GET['pp'];//三方平台
$m = explode('.',$_GET['mm'])[0];//平台通道
$p::pay($m);
