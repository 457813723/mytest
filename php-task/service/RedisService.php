<?php
/**
 * @Author Quincy  2019/1/28 下午4:20
 * @Note
 */


class RedisService
{
    private static $instance = null;
    public static $status = true;


    public static function getInstance()
    {
        if(null === self::$instance){
            try{
                $redis = new \Redis();
                $ip = config('redis_host');
                $port = config('redis_port');
                $pwd = config('redis_pass');

                $redis->connect($ip, $port);
                if($pwd){
                    $result = $redis->auth($pwd);
                    if($result !== true) exception('redis密码不正确');
                }
                self::$instance = $redis;


            } catch (\Exception $e){
                self::$status = $e->getMessage();
            }

        }
        return self::$instance;
    }


    //防止使用new 创建多个实例
    private function __construct()
    {
    }

    //防止clone多个实例
    private function __clone()
    {
    }

    //防止反序列化
    private function __wakeup()
    {
    }
    public function set(){
        echo 123;exit;
//        self::$instance ->set('name','11');
    }
}