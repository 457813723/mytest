<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 20:57
 */

class incomedetail
{
    //收益详情
    public  function index()
    {

        $rid = 11;
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        $sql = "select * from pt_agent where rid = $rid";
        $res1 = $pdo->doSql($sql);

        if(empty($res1)){
            $out = [
                'code'=>2,
                'msg'=>'Players do not exist',
                'content'=>[]
            ];
            echo  json_encode($out);exit;
        }

        $out = [
            'code'=>1,
            'msg'=>'success',
            'content'=>[
                'rid'=>$res1[0]['rid'],
                'up_id'=>$res1[0]['upagent_id'],
                'usable_income'=>$res1[0]['usable_income'],
                'history_income'=>$res1[0]['history_income'],
//                'history_daqu'=>$res1[0]['history_daqu'],
                'used_income'=>$res1[0]['used_income'],
            ]
        ];
        echo  json_encode($out);exit;
    }

    //收益提现记录
    public function deposit_record(){
        $rid = 11;
        $page = get_request('page',0,1);
        $page_size = get_request('size',0,10);
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        $offset = $page_size*$page;
        $sql = "select * from deposit_record where rid = $rid order by time desc limit $offset, $page_size ";
        $res = $pdo->doSql($sql);
        if(empty($res)){
            $out = [
                'code'=>2,
                'count'=>0,
                'msg'=>'暂无提现记录',
                'data'=>[]
            ];
            echo  json_encode($out);exit;
        }
        $data = [];
        foreach($res as $k=>$v){
            if($v['status'] == 1){
                $status = '申请中';
            }else if($v['status'] == 2){
                $status = '成功';
            }else {
                $status = '失败';
            }
            $data[] = [
                'REG_TIME'=>$v['time'],
                'AMOUNT'=>$v['amount'],
                'STATUS'=>$status
            ];

        }
        $out = [
            'code'=>1,
            'ret'=>[
                'list'=>$data
            ]


        ];
        echo  json_encode($out);exit;
    }

    //提现
    public function distribute_to_games(){
        if(!isset($_POST['coins'])){
            echo json_encode(['msg'=>'参数错误','state'=>-1]);exit;
        }
        if($_POST['coins'] <=0){
            echo json_encode(['msg'=>'请输入整数','state'=>-1]);
        }
        $coins = $_POST['coins'];

//        $player_id=parent::$rid;//获取当前登录用户rid
        $player_id = 100102;
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        $sql = "select * from pt_agent where `rid` = $player_id";
        $res = $pdo->doSql($sql);
        if(empty($res)){
            echo json_encode(['msg'=>'rid错误','state'=>-1]);exit;
        }
        $agent_type = $res[0]['agent_type'];

        //开启事务
        $pdo->startTrans();
        //1.先把玩家的收益减掉
        $sql = "
            update pt_agent
            set used_income = used_income + $coins
            where usable_income >= $coins and rid=$player_id
        ";
        $res = $pdo->doSql($sql);
        //更新失败，证明用户可提现余额小于提现金额或者用户信息错误
        if(!$res){
            $pdo->rollback();
            echo json_encode(['msg'=>'可提现收益不足，请刷新后重试']);exit;
        }
        //在pt_deposit_record表添加一条记录
        $reg_time = date('Y-m-d H:i:s',time());
        $sql = "insert into deposit_record values(null,$player_id,$agent_type,$coins,1,'".$reg_time."')";
        $res = $pdo->doSql($sql);
        //添加提现记录失败 回滚
        if(!$res){
            $pdo->rollback();
            echo json_encode(['msg'=>'提现失败','state'=>-1]);
            exit;
        }
        //获取新增数据的ID
        $res = $pdo ->doSql("select last_insert_id() as id");
        $last_id = $res[0]['id'];
        //调用GM命令
//        $body = [
//            "cmd"=>"cash_for_service_diliver",
//            "rid"=>intval($player_id),
//            'order_id'=>md5($player_id.$last_id.time()),
//            "coin"=>$coins*100,
//            'option'=>2//2表示提现
//        ];
//        $pt_game = [];
//        $pt_game['request'] = $body;
//        $callback_url = config('callback_gm');//回调游戏方的地址
//        $key = config('callback_gm_key');//回调游戏方的key
//        $timestamp =time();
//        $token = substr(strtolower(md5($timestamp.json_encode($body).$key)),0,6);
//        $header = [
//            "token: $token",
//            "timestamp: $timestamp",
//            "Content-Type: application/json"
//        ];
//        $res = curl_post($callback_url,$body,$header,true);
//        //请求日志
//        addlog('tixian_request',json_encode($body));
//        //响应日志
//        addlog('tixian_response',json_encode($res));
        //调用
        if(1){
            //更改提现记录状态为3成功
            $sql = "update deposit_record set STATUS = 2 where ID=$last_id";
            $pdo ->doSql($sql);
            $pdo->commit();
            echo json_encode(['ret'=>'提现成功','state'=>1]);exit;
        }else{//失败
//            //游戏方返回失败或者超时
//            if($res['status'] == 1 || $res == false){//重试队列
//                $content = json_encode($body);
//                $datetime = date('Y-m-d H:i:s',time());
//                $sql = "insert into db_system.pt_transactional_retry (TRANS_TYPE,RETRY_TIME,CREATE_TIME,CONTENT) values(6,'$datetime','$datetime','".$content."')";
//                $pdo ->doSql($sql);
//                $pdo->commit();
//                echo json_encode(['msg'=>'申请中','state'=>-1]);exit;
//            }else{//特例：将pt_agent中的AGENT_WITH减回去，并将pt_deposit_record的status置为2 失败
//                $sql = "
//                    update db_detail.pt_agent
//                    set AGENT_WITH = AGENT_WITH - $coins
//                    where  AGENT_ID=$player_id
//                ";
//                $pdo->doSql($sql);
//                $sql = "update db_detail.pt_deposit_record set STATUS = 2 where ID=$last_id";
//                $pdo ->doSql($sql);
//                $pdo->commit();
//                echo json_encode(['msg'=>'提现失败','state'=>-1]);exit;
//            }
        }
    }
}