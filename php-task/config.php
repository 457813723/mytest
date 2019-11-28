<?php
return [
    /*          mysql          */
    'my_host'=>'mysql.bs.com',//地址
    'my_user'=>'admin',//账号
    'my_pass'=>'admin66',//密码
    'my_port'=>'3306',//端口
    /*          mongo          */
    'mongo_host'=>'192.168.1.154',
    'mongo_user'=>'',
    'mongo_pwd'=>'',
    'mongo_port'=>'27017',
    /*           redis          */
    'redis_host'=>'redis.bs.com',//redis ip
    'redis_port'=>'6379',//redis端口
    'redis_pass'=>'',
    /*    平台请求游戏GM命令参数 */
    'callback_gm'=>'43.243.178.7:7260',//回调游戏方地址
    'callback_gm_key'=>'sssdkdhhfjdgfffsssshd123wh',//回调游戏方的key

    //日志目录
    'log_path'=>'/data/logs/php/task/',
    /*   报警短信发送通知人*/
    'msg_receptor'=>'15708464607',

    /*邮件接口url*/
    'email_api' =>'http://192.168.1.121:8080/',
    'email_receptor'=>'457813723@qq.com'
];