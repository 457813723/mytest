<?php
/*
 * 线上配置文件
 * */
return [
    'dbhost'=>'mysql.yh.com',//数据库地址
    'dbuser'=>'root',//数据库账号
    'dbpass'=>'tu3EV2f6a#VD',//数据库密码
    'dbport'=>'3306',//数据库端口

    /*           redis          */
    'redis_host'=>'redis.yh.com',//redis ip
    'redis_port'=>'6379',//redis端口
    'redis_pwd'=>'XBa&hJYAKh6f',//redis密码
    'redis_db'=>0,


    /*        平台回调游戏       */
    'callback_gm'=>'http://gm.yh.com:7260',//回调游戏方地址
    'callback_gm_key'=>'sdfseslsld111e411e11254ds2sd42s4s',//回调游戏方的key


    /*        日志路径       */
    'log_path'=>'/data/logs/php/',
];
