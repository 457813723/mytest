<?php
/*
 * 本地配置文件
 * */

return [
    /*          mysql          */
//    'dbhost'=>'mysql.htx.com',//数据库地址
//    'dbuser'=>'admin',//数据库账号
//    'dbpass'=>'admin66',//数据库密码
//    'dbport'=>'3306',//数据库端口
    'dbhost'=>'192.168.1.232',//数据库地址
    'dbuser'=>'root',//数据库账号
    'dbpass'=>'Platform@mysql2018',//数据库密码
    'dbport'=>'3306',//数据库端口

    /*           redis          */
//    'redis_host'=>'mysql.htx.com',//redis ip
    'redis_host'=>'192.168.1.138',//redis ip
    'redis_port'=>'6379',//redis端口
    'redis_pwd'=>'123456',
    'redis_db'=>3,

    /*        三方支付回调平台    */
    'callback_base_url'=>'http://pay.hw.p9vn.cn',//回调平台地址


    /*        平台回调游戏       */
    'callback_gm'=>'http://192.168.1.4:7260',//回调游戏方地址
    'callback_gm_key'=>'sssdkdhhfjdgfffsssshd123tshh',//回调游戏方的key
    'pay_url'=>'http://192.168.1.171:8002/api/post_html',//游戏方支付url


    /*        日志路径       */
    'log_path'=>'/data/logs/php/',















    /*        三方支付信息        */
    //格子支付
//    'gezi'=>[
//        'url'=>'http://pay.ka7k5.cn/dopay.php',//第三方支付地址
//    ],
//    //海通
//    'haitong'=>[
//        'url'=>'http://www.haitongpay.com/apisubmit',//地址
//        'key'=>'b5820060efa2e2be6bada703bf7622192cec2c1b',//key
//        'customerid'=>10961,//商户id
//        //三方支付回调平台
//        'callback_pt_yb'=>'http://php.pay.718.p9vn.cn/haitong.php',//回调平台地址（异步步）
//        'callback_pt_tb'=>'http://php.pay.718.p9vn.cn/tb.php',//回调平台地址（同步）
//    ],
//
//    //广汇通
//    'ght'=>[
//        'url'=>'https://api.upay.cash',
//        'custid'=>'',
//        'appkey'=>'',
//        //回调
//        'callback_pt_yb'=>'http://php.pay.718.p9vn.cn/ght.php'
//    ],


];
