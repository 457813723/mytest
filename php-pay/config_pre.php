<?php
/*
 * 预发布配置文件
 * */

return [
    /*          mysql          */
    'dbhost'=>'mysql.yh.com',//数据库地址
    'dbuser'=>'root',//数据库账号
    'dbpass'=>'root123123',//数据库密码
    'dbport'=>'3306',//数据库端口
    /*           redis          */
    'redis_host'=>'redis.yh.com',//redis ip
    'redis_port'=>'6379',//redis端口
    'redis_pwd'=>'redis2019',//redis密码
    'redis_db'=>0,

    /*        平台回调游戏       */
    'callback_gm'=>'http://gm.yh.com:6260',//回调游戏方地址
    'callback_gm_key'=>'sssdkdhhfjdgfffsssshd123tshh',//回调游戏方的key

    /*        日志路径       */
    'log_path'=>'/data/logs/php/',



];