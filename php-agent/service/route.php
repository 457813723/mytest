<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/13
 * Time: 11:15
 */

class route
{
    public static $get = [];
    public static $post = [];
    public static function get($route,$cm)
    {
        $g = self::$get;
        $g[$route] = $cm;
        self::$get = $g;
    }
    public static function post($route,$cm)
    {
        $p = self::$post;
        $p[$route] = $cm;
        self::$post = $p;
    }
    /*
     * 'test1/aa1'
     * */
    public static function decode($route)
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $map = self::$$method;
        if(isset($map[$route])){
            $cm = explode('@',$map[$route]);
            return $cm;
        }else{
            echo 'route is error!';exit;
        }
    }
}