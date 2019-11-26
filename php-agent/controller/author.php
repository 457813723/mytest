<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/25
 * Time: 12:05
 */
class author {
    //登录验证
    public function check(){

        $rid = isset($_GET['rid'])?$_GET['rid']:'';
        $code = isset($_GET['code'])?$_GET['code']:'';

        $redis = new Redis();
        $redis->connect(config('redis_host'),config('redis_port'),10);
        if(config('redis_pwd') != ''){
            $redis->auth(config('redis_pwd'));
        }
//        if(config('redis_db') != ''){
//            $redis->select(1);
//        }
//        $redis->select(1);
        $redis_code= $redis->get('game_login_agent:'.$rid);
        //验证登录
        if($redis_code != $code){//验证登录失败
            echo json_encode(['msg'=>'no authority','code'=>0]);exit;
        }else{//验证登录成功
            //删除redis中的rid code
            // $redis->delete('role_backstage:'.$rid);
            //保存用户信息
            session_start();
            $_SESSION['logininfo'] = $rid;
            //跳转到代理后台首页
            $pdo = new mysql(['dbname'=>DB_AGENT]);
            $sql = "select * from db_system.pt_dict_config where TAG='php_agent_index'";
            $res = $pdo->doSql($sql);
            if(empty($res)){
                $out = [
                    'code'=>0,
                    'msg'=>'agent-index config error',
                    'ret'=>[]
                ];
                echo  json_encode($out);exit;
            }
            $url = $res[0]['VAL'];
            header('Location: '.$url);
        }


        //           // $redis->delete('role_backstage:'.$rid);
    }
}