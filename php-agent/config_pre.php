<?php
/*
 * 本地配置文件
 * */

return [
    /*          mysql          */
    'dbhost'=>'mysql.tiantian.com',//数据库地址
    'dbuser'=>'admin',//数据库账号
    'dbpass'=>'ttmysql',//数据库密码
    'dbport'=>'3306',//数据库端口
    /*          mongo          */
    'mongo_host'=>'mongo.tiantian.com',
    'mongo_user'=>'',
    'mongo_pwd'=>'',
    'mongo_port'=>'3717',
    /*           redis          */
    'redis_host'=>'redis.tiantian.com',//redis ip
    'redis_port'=>'6379',//redis端口
    'redis_pwd'=>'ttredis',//redis密码
    'redis_db'=>0,
    /*    平台请求游戏GM命令参数 */
    'callback_gm'=>'gm.tiantian.com:7260',//回调游戏方地址
    'callback_gm_key'=>'sssdkdhhfjdgfffsssshd123wh',//回调游戏方的key

    /*推广-》登录验证-》跳转地址*/
    'authoapi_agentindex'=>'http://admin.tiantian.p9vn.cn/#/homepage',

    /*          游戏下载页面*/
    'game_download_url'=>'http://www.baidu.com',

    /*      域名 */
    'domain'=>'http://admin.tiantian.p9vn.cn/',

    /*          微信公众号配置     */
    'wx_appid'=>'wx71c9f7091c7d9f9b',//appid
    'wx_secret'=>'f2fc2490c1c4558e8b1da5ca5e0e7804',//secret

    /*      新浪微博source */
    'sina_source'=>'4213499036',
    /*      日志     */
    'log_path' =>'/data/logs/php/agent/'
];
