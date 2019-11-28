<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 16:47
 */
class common{
    public static $rid = '';
    public function __construct()
    {
        //权限验证
        session_start();
//        //登录验证
        if(empty($_SESSION['logininfo'])){
            echo '请先登录';
            exit;
        }
//        //菜单权限验证
        $rid = $_SESSION['logininfo'];
//        $rid = 2004937;
        self::$rid = $rid;
        $pdo = new mysql(['dbname'=>'db_detail']);
        $sql = "select agent_type from db_detail.pt_agent where rid=$rid";
        $res = $pdo->doSql($sql);
        if(empty($res)){
            echo json_encode(['msg'=>'暂无此用户','state'=>-1]);exit;
        }
        $user_type = $res[0]['agent_type'];
        $m = $_GET['m'];
        $m = explode('.',$m)[0];
        if($user_type != 2){
            $menuemap = getmenue();
            $menuemap = $menuemap[$user_type];
            if(!in_array($m,$menuemap)){
                echo json_encode(['msg'=>'no permission','state'=>-1]);exit;
            }
        }
    }
}