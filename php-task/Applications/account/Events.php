<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
use Workerman\Lib\Timer;
/** 40:50
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{

    protected static $mysql;
    protected static $mongo;
    protected static $redis;
    protected static $a;
    protected static $b;
    public static function onConnect($client_id)
    {

    }

    public static function onMessage($client_id, $messages)
    {

    }

    public static function onWorkerStart($businessWorker)
    {
        require_once dirname(dirname(dirname(__FILE__))).'/service/MongoService.php';
        require_once dirname(dirname(dirname(__FILE__))).'/service/MysqlService.php';
        self::$a = 0;
        self::$b = 0;
        $redis = new Redis();
        $redis->connect(config('redis_host'), config('redis_port'), 2);
        if (config('redis_pass') != '') {
            $redis->auth(config('redis_pass'));
        }
        $redis->select(4);
        self::$redis = $redis;

        //mongo
        $mongo = new MongoService();
        self::$mongo = $mongo;
        //mysql   58
        $mysql = new MysqlService(['dbname' => 'db_detail']);
        self::$mysql = $mysql;
        // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
        if ($businessWorker->id === 0) {
            // 定时器1
            Timer::add(2, function () {
                self::account(0);
            });
        } else if ($businessWorker->id === 1) {
            Timer::add(1, function () {
//                self::account(1);
            });
        } else if ($businessWorker->id === 2) {

            Timer::add(1, function () {
//                self::account(2);
            });
        } else if ($businessWorker->id === 3) {
            //处理上一个小时没处理完的数据
            Timer::add(1, function () {
//                self::account(3);
            });
        }else if ($businessWorker->id === 4) {
            //处理上一个小时没处理完的数据
            Timer::add(1, function () {
//                self::account(4);
            });
        }else if ($businessWorker->id === 5) {
            //处理上一个小时没处理完的数据
            Timer::add(1, function () {
//                self::account(5);
            });
        }else if ($businessWorker->id === 6) {
            //处理上一个小时没处理完的数据
            Timer::add(1, function () {
//                self::account('old');
            });
        }
    }
    public static function addtestData($s){
        $ymd = date('Y_m_d');
        $coll = $ymd . '.pt_service_2';
        $data = [];

        for($i=0;$i<10;$i++){
            $rid = rand(200000,399999);
            $data[] = [
                'rid'=>$rid,
                'orginId'=>'aaa',
                'clubId'=>234,
                'userId'=>123,
                'service_charge'=>10000,
                'time_stamp'=>$s+3,
                'flag'=>0
            ];
        }
        self::$mongo->add($coll, $data);
    }

    //消费原始服务费表产生中间表
    public static function create_pt_service($mod)
    {
        $sql = "select order_id,pt_user_id,real_bonus,bet_time from db_jingcai.pt_order where status = 5 limit 50";
        $res = self::$mysql->doSql($sql);
        $ymd = date('Y_m_d');
        $h = date('H');
        //mongo，数据库以日期命名
        $collection = $ymd . '.servicechargelog_' . $h;
        $filter = [
            'time_stamp' => ['$mod' => [2, $mod]],//对5取模
            'status' => ['$ne' => 1]
        ];
        $options = [
            'projection' => [], //选需要的字段，id是默认的
            'limit' => 500,
            'skip' => 0
        ];
        $res = self::$mongo->query($collection, $filter, $options);
        //t_deduct_01  _id=>originaId   rid=>userId   gameid=>gameId id=>fightId   service_charge=>amount
        //aftertotal=>currentMoney   time_stamp=>createTime   flag=>1
        $count_day = date('Ymd', time());
        $time = date('Y-m-d H:i:s', time());


        if (!empty($res)) {
            //添加到服务明细表
            $data = [];
            foreach ($res as $k => $v) {
//                $data = [[
//                    'userId' => $v['_id'],
//                    'originid' => $v['userId'],
//                    'origin_service'=>$v['service_charge'] ,
//                    'contribid' => $v['userId'],
//                    'contri_ratio'=>$ratio,
//                    'receiptid' => $v['userId'],
//                    'receipt_ratio'=>$ratio,
//                    'fee' => $fee,
//                    'time_stamp' => $time,
//                    'fee_time'=>$v['time_stamp'],
//                ]];


                $a = [
                    '_id' => new \MongoDB\BSON\ObjectId($v['_id']),
                    'originId' => $v['_id'],
                    'userId' => $v['rid'],
                    'gameId' => $v['gameid'],
                    'fightId' => $v['id'],
                    'amount' => $v['service_charge'],
                    'currentMoney' => $v['aftertotal'],
                    'time_stamp' => $v['time_stamp'],
                    'flag' => 0
                ];
                $data[] = $a;
//                self::$mongo->update($collection,json_encode($data));
                //将服务明细放进redis中供 结算使用
//                self::$redis->rPush('deduct',json_encode($a));
            }
            $coll = $ymd . '.t_deduct_' . self::getShareByhour($h);
            self::$mongo->add($coll, $data);
            //更改mongo 原始服务记录表 结算状态
            $data = [];
            foreach ($res as $k => $v) {
                $obj_id = new \MongoDB\BSON\ObjectId($v['_id']);
                $data[] = [
                    ['_id' => $obj_id],
                    ['$set' => ['status' => 1]],
                    ['multi' => true, 'upsert' => false]
                ];
            }
            self::$mongo->update($collection, $data);
        }
    }



    //结算
    public static function account($mod)
    {
        $time = time();
        $ymd = date('Y_m_d',$time);
        $hh = date('H',$time);
        $dbh  = getClubShare($hh);
//        if($mod === 'old'){
//            if($dbh == 0){
//                $ymd = date('Y_m_d',$time-24*3600);
//                $dbh=3;
//            }else{
//                $dbh = $dbh - 1;
//            }
//            $filter = [
//                'flag' => ['$eq' => 0]
//            ];
//        }else{
//            $filter = [
//                'userId' => ['$mod' => [6, $mod]],//对3取模
//                'flag' => ['$eq' => 0]
//            ];
//        }
        $filter = [
//            'userId' => ['$mod' => [6, $mod]],//对3取模
            'flag' => ['$eq' => 0]
        ];
        $options = [
            'projection' => ['_id' => 1, 'rid' => 1, 'service_charge' => 1, 'time_stamp' => 1, 'flag' => 1,'orginId'=>1,'clubId'=>1,'userId'=>1], //选需要的字段，id是默认的
            'limit' => 500,
            'skip' => 0
        ];

        $collection = $ymd . '.pt_service_'.$dbh;
        $res = self::$mongo->query($collection, $filter, $options);
//        echo $mod.':::'.count($res).'----';

        //redis 中保存玩家类型,层级信息，分成比例
        //2000115=>['agent_type'=>1,'upagent_id'=>2000115,'ratio'=>30]
        $update_data = [];
        foreach ($res as $k => $v) {
            //修改服务费表service状态flag=1
            $obj_id = new \MongoDB\BSON\ObjectId($v['_id']);
            $update_data[] = [
                ['_id' => $obj_id],
                ['$set' => ['flag' => 1]],
                ['multi' => true, 'upsert' => false]
            ];

            $sql = "select * from pt_agent where rid = ".$v['userId'];
            $res = self::$mysql->doSql($sql);
            if(empty($res)){
                addlog('account/agent_account_error',"服务费orginId为".$v['orginId']."中玩家".$v['userId']."不存在于pt_agent",1);
                //添加日志：玩家不存在
                continue;
            }
            //当前玩家代理类型
            $one_agent_type = $res[0]['agent_type'];
            //redis中保存的代理关系是永久的
            //玩家不是代理，不在任何一棵树中，不保存到redis中,不结算
            if($res[0]['upagent_id'] == 0 && $res[0]['agent_type']==1){
                contine;
                //金字塔顶端的人，不保存到redis中，如果以后该人去绑定到其他代理的下面才保存到redis中
            }else if($res[0]['upagent_id'] == 0  && $res[0]['agent_type'] == 2){
                $map[] = $res[0]['rid'];

            }else{
                //检查整条代理线是否是环状
                $map  = [];//代理线
                $map[] = $v['userId'];
                self::check_agent_line($v['userId'],$map);
                if(empty($map)){
                    continue;
                }
            }

            $ymd1 =date('Ymd',$v['time_stamp']);//pt_club_gather中的日期取决于原始服务费中的时间戳
            $hhh = date('H',$v['time_stamp']);//mongo明细表的分表取决于原始服务费中的时间戳
            //结算 代理线
            foreach($map as $q=>$h){

                //处理了一条原始服务费就修改一条服务费的flag
                self::$mongo->update($collection, $update_data);
                //如果第一个玩家是代理
                if ($q ==0 && $one_agent_type==2) {
                    //获取代理的比例
                    $ratio = self::getRatio($h);
                    $fee = $v['service_charge'] * 0.92  * $ratio / 100;
                    $fee = intval($fee);
                    $fee_mysql = intval($fee) / 100;
                    //1.添加服务费明细到mongodb  pt_service_detail
                    $data = [[
                        'service_id' => $v['_id'],
                        'originid' => $v['userId'],//原始服务费产生者
                        'origin_service'=>$v['service_charge'] ,//服务费
                        'contribid' => $v['userId'],//贡献者id
                        'contri_ratio'=>$ratio,//贡献者比例
                        'receiptid' => $v['userId'],//接收者id
                        'receipt_ratio'=>$ratio,//接收者比例
                        'fee' => $fee,//收益
                        'time_stamp' => $time,//结算时间
                        'fee_time'=>$v['time_stamp'],//服务费产生时间
                    ]];
                    self::$mongo->add($ymd . '.pt_service_detail_'.$hhh, $data);
                    //2.更新日统计表pt_count_gather
                    $gather_time = date('Y-m-d H:i:s',time());
                    $count_time = date('Y-m-d H:i:s',$v['time_stamp']);
                    $sql = "update pt_count_gather set COUNT_FEE=COUNT_FEE+".$fee_mysql.",GATHER_TIME='$gather_time' where COUNT_DAY=$ymd1 and CONTRIB_ID=".$v['userId']."  and RECEIPTOR = ".$v['userId'];
                    $affect = self::$mysql->doSql($sql);
                    if($affect === 0 ){
                        $sql = "insert into pt_count_gather values(null,$ymd1,".$v['userId'].",2,".$fee_mysql.",".$v['userId'].",'".$count_time."','".$gather_time."',0)";
                        try{
                            $res = self::$mysql->doSql($sql);
                        }catch (Exception $e){
                            //捕获唯一键报错
                            if(strstr($e->getMessage(),'query_index')){
                                $sql = "update pt_count_gather set COUNT_FEE=COUNT_FEE+".$fee_mysql.",GATHER_TIME='$gather_time'  where COUNT_DAY=$ymd1 and CONTRIB_ID=".$v['userId']."  and RECEIPTOR = ".$v['userId'];
                                self::$mysql->doSql($sql);
                            }
                        }
                    }
                    //3.更新player表中的代理收益
                    self::$mysql->doSql("update pt_agent set history_income = history_income +$fee_mysql where rid=" . $v['userId']);
                }
                //只要不是金字塔定端的人  都要为上一级产生贡献值

                if($q != count($map)-1){
                    $rid = $h;
                    $up_id = $map[$q+1];//上级id
                    //在redis中获取代理的分成比例
                    $ratio = self::getRatio($rid);//下级比例
                    $up_ratio = self::getRatio($up_id);//上级比例
                    $service = $v['service_charge'];//原始服务费
                    $service_id = $v['_id'];//原始服务费id
                    $originid = $v['userId'];//原始贡献者id
                    $contribid = $rid;//下级id
                    $fee = $service  * ($up_ratio / 100 - $ratio/100);//mongo明细按分存
                    $fee = intval($fee);
                    $fee_mysql = intval($fee) /100;
                    if ($fee < 0) {//因为某种原因 上级的分层比例小于下级的分成比例。
                        addlog('account/agent_account_error',"代理收益比例错误 下级比例高于上级比例：$rid : $ratio --  $up_id : $up_ratio",1);
                        return;
                    }

                    //1.添加服务费明细到mongodb  pt_service_detail
                    $data = [[
                        'service_id' => $service_id,
                        'originid' => $originid,
                        'origin_service'=>$service ,
                        'contribid' => $contribid,
                        'contri_ratio'=>$ratio,
                        'receiptid' => $up_id,
                        'receipt_ratio'=>$up_ratio,
                        'fee' => $fee,
                        'time_stamp' => $time,
                        'fee_time'=>$v['time_stamp']
                    ]];
                    self::$mongo->add($ymd . '.pt_service_detail_'.$hhh, $data);
                    //2.更新日统计表pt_count_gather
                    $gather_time = date('Y-m-d H:i:s',time());
                    $count_time = date('Y-m-d H:i:s',$v['time_stamp']);
                    $sql = "update pt_count_gather set COUNT_FEE=COUNT_FEE+".$fee_mysql.",GATHER_TIME='$gather_time' where COUNT_DAY=$ymd1 and CONTRIB_ID=".$contribid."  and RECEIPTOR = ".$up_id;
                    $affect = self::$mysql->doSql($sql);
                    if($affect === 0 ){
                        $sql = "insert into pt_count_gather values(null,$ymd1,".$contribid.",1,".$fee_mysql.",".$up_id.",'".$count_time."','".$gather_time."',0)";

                        try{
                            self::$mysql->doSql($sql);
                        }catch (Exception $e){
                            //捕获唯一键报错
                            if(strstr($e->getMessage(),'query_index')){
                                $sql = "update pt_count_gather set COUNT_FEE=COUNT_FEE+".$fee_mysql.",GATHER_TIME='$gather_time' where COUNT_DAY=$ymd1 and CONTRIB_ID=".$contribid."  and RECEIPTOR = ".$up_id;
                                self::$mysql->doSql($sql);
                            }
                        }
                    }
                    //3.更新player表中的代理收益
                    self::$mysql->doSql("update pt_agent set history_income = history_income +$fee_mysql where rid=" . $up_id);
                }
            }

            $map = [];
            unset($map);
        }

    }

    //检查代理关系是否有环状
    public static function check_agent_line($rid,&$map){
        $up_id = self::$redis->get('agent_up_'.$rid);
        if (empty($up_id)) {
            $s = self::$mysql->doSql("select rid,agent_type,upagent_id from pt_agent where rid=" . $rid);
            if (empty($s)) {
                //查询到某个上级不存在 add_log
                addlog('account/agent_account_error',"检测环状：上级ID $rid pt_agent，截止该条结算线，进行结算",1);
                return $map;
            }
            //如果是金字塔顶端的人就不存放到redis
            if($s[0]['agent_type'] == 2 && $s[0]['upagent_id'] == 0){

            }else{
                //保存玩家信息到redis
                self::$redis->set('agent_up_'.$rid, intval($s[0]['upagent_id']));
            }
            $up_id = $s[0]['upagent_id'];
        }
        if(in_array($up_id,$map)){
            $error_map = $map;
            $error_map[] = $up_id;
            echo 'agent_account_error_huanzhuang：';
            var_dump($error_map);
            addlog('account/agent_account_error',"代理环状错误：".json_encode($error_map),1);
            exit;
        }


        //金字塔顶端的人的上级ID为0
        if($up_id >10){
            $map[] =$up_id;
            self::check_agent_line($up_id,$map);
        }else{
            return $map;
        }
    }

    //获取代理分成比例
    public static function getRatio($rid){
        $s = self::$mysql->doSql("select ratio from pt_agent where rid=" . $rid);
        if (empty($s)) {
            return 0;
        }
        return $s[0]['ratio'];
    }
}
