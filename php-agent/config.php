<?php
/*
 * 本地配置文件
 * */

return [
    /*          mysql          */
    'dbhost'=>'192.168.1.232',//数据库地址
    'dbuser'=>'root',//数据库账号
    'dbpass'=>'Platform@mysql2018',//数据库密码
    'dbport'=>'3306',//数据库端口
    /*          mongo          */
    'mongo_host'=>'192.168.1.138',
    'mongo_user'=>'',
    'mongo_pwd'=>'',
    'mongo_port'=>'27017',
    /*           redis          */
    'redis_host'=>'192.168.1.138',//redis ip
    'redis_port'=>'6379',//redis端口
    'redis_pwd'=>'123456',
    //代理数据库
    'db_agent'=>'db_detail',





    /*    平台请求游戏GM命令参数 */
    'callback_gm'=>'43.243.178.72:7260',//回调游戏方地址
    'callback_gm_key'=>'sssdkdhhfjdgfffsssshd123wh',//回调游戏方的key

    /*          游戏登录跳转地址*/
    'authoapi_agentindex'=>'http://admin.tiantian.p9vn.cn/#/homepage',

    /*          游戏下载页面*/
    'game_download_url'=>'http://www.baidu.com',
    /*          域名        */
    'domain'=>'http://admin.tiantian.p9vn.cn/',

    /*          微信公众号配置     */
    'wx_appid'=>'wx71c9f7091c7d9f9b',//appid
    'wx_secret'=>'f2fc2490c1c4558e8b1da5ca5e0e7804',//secret
    /*          新浪微博source      */
    'sina_source'=>'4213499036',

    /*          日志     */
    'log_path' =>'../logs/php/agent/'
];
