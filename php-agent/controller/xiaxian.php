<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 20:57
 */

class xiaxian
{
    public  function index()
    {
        $rid = 11;
        $page = get_request('page',0,1);
        $page_size = get_request('size',0,10);
        $keyworld = get_request('keyworld',0,'');
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        $offset = $page_size*$page;
        if(!empty($keyworld)){
            $sql = "select rid,agent_type,bind_time,ratio from pt_agent where upagent_id = $rid  limit $offset, $page_size";
        }else{
            $sql = "select rid,agent_type,bind_time,ratio from pt_agent where upagent_id = $rid  limit $offset, $page_size";
        }
        $res = $pdo->doSql($sql);
        $data = [];
        foreach($res as $k=>$v){
            if($v['agent_type'] == 1){
                $userType = '玩家';
            }else if($v['agent_type'] == 2){
                $userType= '代理';
            }else {
                $userType = $v['agent_type'];
            }
            $res[$k]['rolename'] = '玩家'.$v['rid'];
            $res[$k]['ratio'] = intval($v['ratio']).'%';

            $data[] = [
                'playerId'=>$v['rid'],
                'nickName'=>'代理'.rand(0,9),
                'userType'=>$userType,
            ];
        }

        $out = [
            'code'=>1,
            'count'=>count($res),
            'msg'=>'success',
            'ret'=>['rows'=>$data]

        ];
        echo  json_encode($out);exit;
    }

    public function addAgent(){
//        $rid = parent::$rid;//当前登录用户的rid
        $rid = 11;
        $playerid = $_POST['playerId'];
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        //开启事务
//        $pdo->startTrans();
        $sql = "update pt_agent set agent_type=2 where rid=$playerid and upagent_id=$rid";
        $res = $pdo->doSql($sql);
        if(!$res){
//            $pdo->rollback();
            echo json_encode(['msg'=>'添加失败:该玩家不是你的下级','code'=>-1]);exit;
        }
//        $regtime = date('Y-m-d H:i:s',time());
//        $sql = "insert into pt_agent
//                (AGENT_ID,UP_AGENT_ID,AGENT_TYPE,AGENT_INCOME,AGENT_WITH,TEA_HOUSE_NUM,TODAY_SCAN_NUM,TODAY_ACTIVE_NUM,REG_TIME,STATUS)
//                values($playerid,$rid,1,0.00,0.00,0,0,0.00,'".$regtime."',1)";
//        $res = $pdo->doSql($sql);
//        if(!$res){
//            $pdo->rollback();
//            echo json_encode(['msg'=>'添加失败','state'=>-1]);exit;
//        }
//        $pdo->commit();
        echo json_encode(['msg'=>'添加成功，请通知该代理刷新页面，使用代理权限','code'=>1]);exit;
    }

    /*
     * 设置代理比例
     * */
    public function setRatio(){
        if(!isset($_POST['agent_id']) || !isset($_POST['prorata'])){
            echo json_encode(['msg'=>'参数错误','state'=>-1]);exit;
        }
        $agent_id = $_POST['agent_id'];
        $prorata = $_POST['prorata'];
        if($prorata <0){
            echo json_encode(['msg'=>'比例不能为负数','state'=>-1]);exit;
        }
//        $player_id=parent::$rid;//获取当前登录用户rid
        $rid = 2000112;
        $pdo = new mysql(['dbname'=>'layui']);
        //验证是不是自己的下线,和是不是代理
        $sql = "select * from player where rid=$agent_id and upagent_id=$rid and agent_type = 2";
        $res = $pdo->doSql($sql);
        if(empty($res)){
            echo json_encode(['msg'=>'非法请求','state'=>-1]);exit;
        }
        //该用户之前的比例
        $history_ratio = $res[0]['ratio'];
        //查询自己的比例
        $sql = "select * from player where rid = $rid";
        $res = $pdo->doSql($sql);
        $myrotio = $res[0]['ratio'];
        //验证不能高于自己并且不能低于之前的比例 并且大于0
        if($history_ratio<$prorata && $prorata<$myrotio){
            $sql = "update player set ratio=$prorata where rid=$agent_id";
            $res = $pdo->doSql($sql);
            if(!$res){
                echo json_encode(['msg'=>'设置失败','state'=>-1]);exit;
            }else{
                echo json_encode(['ret'=>'更新成功','state'=>1]);exit;
            }
        }else{
            echo json_encode(['msg'=>'代理比例不合法','state'=>-1]);exit;
        }
    }
}