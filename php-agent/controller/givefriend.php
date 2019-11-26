<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/20
 * Time: 18:21
 */

class givefriend extends common
{
    /*
     * 查询保险箱金币
     * */
    public function safe_box_coins(){
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        //获取登录用户的rid
        $player_id=parent::$rid;
        $sql = "select bankpack from game_1.role_money where rid = $player_id";
        $res = $pdo->doSql($sql);
        if(empty($res)){
            echo json_encode(['msg'=>'暂无此用户','state'=>-1]);exit;
        }
        $bankpack = $res[0]['bankpack']/100;
        echo json_encode(['ret'=>$bankpack,'state'=>1]);
    }
    /*
     * 查询被赠送人是否存在
     * */
    public function searchPlayer()
    {
        if(!isset($_POST['playerId'])){
            echo json_encode(['msg'=>'参数错误','state'=>-1]);exit;
        }
        $to = $_POST['playerId'];
        $pdo = new mysql(['dbname'=>'db_system']);
        $sql = "select * from db_system.player where USER_ID=$to";
        $res = $pdo ->doSql($sql);
        $out = [
            'ret'=>[],
            'state'=>1
        ];
        if(empty($res)){
            echo json_encode(['ret'=>['searchPlayerId'=>false],'state'=>1]);exit;
        }else{
            echo json_encode(['ret'=>['searchPlayerId'=>true,'searchPlayerInfo'=>$res[0]],'state'=>1]);exit;
        }
    }

    /*
     * 赠送亲友
     * */
    public function give()
    {
        if(!isset($_POST['coins'])){
            echo json_encode(['msg'=>'参数错误','state'=>-1]);exit;
        }
        if($_POST['coins'] <=0 ){
            echo json_encode(['msg'=>'参数错误','state'=>-1]);exit;
        }
        $coins = $_POST['coins'];
        $to = $_POST['playerId'];
        $player_id=parent::$rid;//获取当前登录用户rid
        $pdo = new mysql(['dbname'=>'db_detail']);
        $time =date('Y-m-d H:i:s',time()) ;
        //开启事务
        $pdo->startTrans();
        //业务
        $sql = "insert into pt_give_friend_record values(null,$player_id,$to,$coins,1,'$time','$time')";
        $res = $pdo->doSql($sql);
        //添加提现记录失败 回滚
        if(!$res){
            $pdo->rollback();
            echo json_encode(['msg'=>'赠送失败','state'=>-1]);
            exit;
        }
        //获取新增数据的ID
        $res = $pdo ->doSql("select last_insert_id() as id");
        $last_id = $res[0]['id'];
        $body = [
            "cmd"=>"playerSendToPlayer",
            "from_rid"=>intval($player_id),
            'to_rid'=>intval($to),
            "coin"=>$coins*100,
            'backstage_id'=>md5($player_id.$to.time())//2表示提现
        ];
        $pt_game = [];
        $pt_game['request'] = $body;
        $callback_url = config('callback_gm');//回调游戏方的地址
        $key = config('callback_gm_key');//回调游戏方的key
        $timestamp =time();
        $token = substr(strtolower(md5($timestamp.json_encode($body).$key)),0,6);
        $header = [
            "token: $token",
            "timestamp: $timestamp",
            "Content-Type: application/json"
        ];
        $res = curl_post($callback_url,$body,$header,true);
        //请求日志
        addlog('givefriend_request',json_encode($body));
        //响应日志
        addlog('givefriend_response',json_encode($res));
        if($res['status'] === 0){//成功
            //更改赠送记录状态为2成功
            $sql = "update db_detail.pt_give_friend_record set STATUS = 2 where RECORD_ID=$last_id";
            $pdo ->doSql($sql);
            $pdo->commit();
            echo json_encode(['ret'=>'赠送成功','state'=>1]);exit;
        }else{//失败
            if($res['status'] == 1 || $res == false) {//重试队列
                $content = [
                    'recordId'=>$body['backstage_id'],
                    'userId'=>$body['from_rid'],
                    'incomeId'=>$body['to_rid'],
                    'money'=>$coins
                ];
                $content = json_encode($content);
                $datetime = date('Y-m-d H:i:s',time());
                $sql = "insert into db_system.pt_transactional_retry (TRANS_TYPE,RETRY_TIME,CREATE_TIME,CONTENT) values(6,'$datetime','$datetime','".$content."')";
                $pdo ->doSql($sql);
                $pdo->commit();
                echo json_encode(['msg'=>'申请中','state'=>-1]);exit;
            }else{//
                $sql = "update db_detail.pt_give_friend_record set STATUS = 3 where RECORD_ID=$last_id";
                $pdo ->doSql($sql);
                $pdo->commit();
                echo json_encode(['msg'=>'赠送失败','state'=>-1]);
            }
        }
    }

    public function giveRecord()
    {
        $page = $_POST['page'];
        $size = $_POST['size'];
        $offsite = $size*$page;
        $player_id=parent::$rid;//获取当前登录用户rid
        $pdo = new mysql(['dbname'=>'db_detail']);
        $sql = "
            select  
              INCOME_ID,
              MONEY,
              REG_TIME,
              case STATUS
                    when 1 then '发送中'
                    when 2 then '已发送'
                    else '发送失败' end as STATUS
              from pt_give_friend_record
              where USER_ID = $player_id
              order by REG_TIME desc 
              LIMIT $offsite,$size
        ";
        $res = $pdo->doSql($sql);
        $out = [
            'ret'=>[
                'rows' =>[]
            ],
            'state'=>1
        ];
        if(empty($res)){
            echo json_encode($out);exit;
        }
        $out['ret']['rows'] = $res;
        echo json_encode($out);
    }
}