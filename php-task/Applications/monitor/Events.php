<?php
use \GatewayWorker\Lib\Gateway;
use Workerman\Lib\Timer;
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    protected static $mysql;
    protected static $mongo;
    protected static $redis;
    protected static $aop;//支付宝对象
    protected static $aop_request_create;//支付宝创建订单
    protected static $aop_request_query;//支付宝查询订单
    protected static $inc1;
    protected static $inc2;
    protected static $inc3;
    protected static $inc4;
    protected static $inc5;
    protected static $inc6;
    protected static $inc7;
    public static function onConnect($client_id)
    {
    }
    public static function onMessage($client_id, $messages)
    {
    }
    public static function onWorkerStart($businessWorker)
    {
        require_once dirname(dirname(dirname(__FILE__))).'/service/MongoService.php';
        $mongo = new MongoService();
        self::$mongo = $mongo;
        require_once dirname(dirname(dirname(__FILE__))).'/service/MysqlService.php';
        self::$mysql = new MysqlService(['dbname'=>'db_detail']);
        $redis = new Redis();
        $redis->connect(config('redis_host'),config('redis_port'),2);
        if(config('redis_pass') != ''){
            $redis->auth(config('redis_pass'));
        }
        $redis->select(3);
        self::$redis=$redis;
        // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
        if ($businessWorker->id === 0)
        {
            // 定时器1
            Timer::add(100,function(){
                addlog('monitor/bs_heart_test','monitor_type:coinlog',1);//请求GM 添加日志
                self::monitorCoinChange();
            });
            //监控保险箱金币
        }else if($businessWorker->id === 1){

            Timer::add(150,function(){
                addlog('monitor/bs_heart_test','monitor_type:bankpack',1);//请求GM 添加日志
                self::monitorBank();
            });
        }else if($businessWorker->id === 2){
            //监控代理收益

            Timer::add(120,function(){
                addlog('monitor/bs_heart_test','monitor_type:agent_fee',1);//请求GM 添加日志
                self::monitorAgentFee();
            });
        }else if($businessWorker->id === 3){
            //监控机器人输赢
            Timer::add(130,function(){
                addlog('monitor/bs_heart_test','monitor_type:robot_win_lose',1);//请求GM 添加日志
                self::monitorRobot();
            });
        }else if($businessWorker->id === 4){
            //统计各小游戏每天的局数和服务费
            Timer::add(300,function(){
                addlog('monitor/bs_heart_test','monitor_type:gamerecord_service',1);//请求GM 添加日志
                self::Statistics_service_gamerecord();
            });
        }else if($businessWorker->id === 5){
            //统计玩家每天的输赢值和胜率
                Timer::add(1,function(){
                    if(date('H:i:s',time()) == '06:10:01'){
                      self::rate_profit();
                    }
                });
        }else if($businessWorker->id === 6){
            //统计下发超过三次的账号
            Timer::add(300,function(){
                addlog('monitor/bs_heart_test','monitor_type:xiafa3',1);//请求GM 添加日志
                self::xiafa3();
            });

        }else if($businessWorker->id === 7){
            //活动发放（坐庄奖励）
//            Timer::add(1,function(){
//                if(date('H:i:s',time()) == '00:30:00'){
//                    self::huodong_zuozhuang();
//                }
//            });
        }else if($businessWorker->id === 8){
            //统计玩家金币变动超过5w的游戏结算
            Timer::add(1,function(){
                if(date('H:i:s',time()) == '07:01:01'){
//                    self::win_more_five();
                }
            });
        }else if($businessWorker->id === 9){
            //统计玩家半小时内赢钱超过3w的
            Timer::add(1,function(){
                if(date('H:i:s',time()) == '07:30:01'){
//                    self::minute_30();
                }
            });
        }else if($businessWorker->id === 10){
        }
        //
    }
    /*
     * 监控金币变动
     * */
    public static function monitorCoinChange(){
        $db = date('Y_m_d',time());
        $h =date('H',time());
        $collection = $db . '.coinlog_' . $h;
        $filter = [
//            'time_stamp'=>['$mod'=>[2,$mod]],//对5取模
//            'status' => ['$ne' => 1]
        ];
        $options = [
            'projection' => ['beforetotal'=>1,'num'=>1,'aftertotal'=>1,'key'=>1,'rid'=>1,'reason'=>1], //选需要的字段，id是默认的
//            'limit'=>500,
//            'skip'=>0
        ];
        $num = 0;
        $res = self::$mongo->query($collection, $filter, $options);
        $str = '';
        foreach($res as $k => $v){
            if($v['beforetotal'] + $v['num'] != $v['aftertotal']){
                $num = $num +($v['beforetotal']+$v['num'] - $v['aftertotal']);
                $str .= json_encode($v)."\n<br/>";
            }
        }
        if($str != ''){
            addlog('monitor/coinchange/record_'.$db.'_'.$h,$str,0);
            addlog('monitor/coinchange/total_'.$db.'_'.$h,'当前分片至少有 '.$num.' 数据异常'."\n",0);
//            sendEmail($db.'_'.$h.':coin变动异常(分为单位)',$str);
            //【和天下】数据异常报警:@
            $msg = "验证码是:jb:".$num.'(fen)'.$db.'/'.$h;
            $s = self::isSendMsg('php_coinlog');
            if($s == false){
                sendMsg($msg);
                self::$redis->set('php_coinlog',1,3600);
            }
        }
        unset($data);
        unset($res);
    }
    /*
     * 监控保险箱金币
     * */
    public static function monitorBank(){
        $db = date('Y_m_d',time());//数据库
        $collection = $db . '.bankpack';
        $filter = [
//            'time_stamp'=>['$mod'=>[2,$mod]],//对5取模
//            'status' => ['$ne' => 1]
        ];
        $options = [
            'projection' => ['beforetotal'=>1,'num'=>1,'aftertotal'=>1,'rid'=>1,'reason'=>1], //选需要的字段，id是默认的
//            'limit'=>500,
//            'skip'=>0
        ];
        $num = 0;
        $res = self::$mongo->query($collection, $filter, $options);
        $str = '';
        foreach($res as $k => $v){
            if($v['beforetotal'] + $v['num'] != $v['aftertotal']){
                $num = $num +($v['beforetotal']+$v['num'] - $v['aftertotal']);
                $str .= json_encode($v)."\n<br/>";
            }
        }
        if($str != ''){
            addlog('monitor/bank/record_'.$db,$str,0);//请求GM 添加日志
            addlog('monitor/bank/total_'.$db,'当前分片至少有 '.$num.' 数据异常'."\n",0);
//            sendEmail($db.':bank金币变动异常(分为单位)',$str);
            $msg = "验证码是:bxx:".$num.'(fen)'.$db;
            $s = self::isSendMsg('php_bank');
            if($s == false){
                sendMsg($msg);
                self::$redis->set('php_bank',1,3600);
            }
        }
        unset($data);
        unset($res);
    }
    /*
     * 监控代理收益总量小于服务费的二分之一
     * */
    public static function   monitorAgentFee(){
        $rate = 0.46;//五级分层比例和
        $db = date('Y_m_d');
        $h =date('H');
        $hh = getShareByhour($h);
        //计算当前分片的服务费
        $service_charge = 0;
        $cmd = [
            'aggregate' => 't_deduct_'.$hh,
            'pipeline' => [
                ['$group' => [  '_id' => ['flag'=>'$flag'],'amount_count' => ['$sum'=>'$amount'] ] ],
            ],
            'cursor' => new \stdClass,
        ];
        $res = self::$mongo->command($db,$cmd);
        foreach($res as $v){
            if($v->_id->flag == 1){
                $service_charge =  $v->amount_count;
            }
        }
        //计算当前分片的代理收益
        $income = 0;
        $cmd = [
            'aggregate' => 't_income_detail_'.$hh,
            'pipeline' => [
                ['$group' => [  '_id' => ['flag'=>'$flag'],'income_count' => ['$sum'=>'$income'] ] ],
            ],
            'cursor' => new \stdClass,
        ];
        $res = self::$mongo->command($db,$cmd);
        foreach($res as $v){
            if($v->_id->flag == 1){
                $income =  $v->income_count;
            }
        }

        //代理收益大于二分之一总服务费 就报警
        if($income > ($rate*$service_charge/100 + 50)){
            $num = $income * 100 -$rate*$service_charge;
            $msg = "验证码是:dl:".$num.'(fen)--：'.$db.'/'.$hh;
            addlog('monitor/agentincome/'.$db.'_'.$hh,'当前分片至少有 '.$num.'(分为单位) 数据异常'."\n",0);
//            sendEmail($db.'分片:'.$hh.':agent_error(分为单位)','超出金币:'.$num);
            $s = self::isSendMsg('php_agent');
            if($s == false){
                sendMsg($msg);
                self::$redis->set('php_agent',1,3600);
            }
        }
        unset($res);
    }
    /* 监控机器人输赢：每天不能超过2W  5 5  9.5 0  -0.5+0.5=0   */
    public static function monitorRobot(){
        $sql = "select sum(RECORD_VAL_3) + sum(RECORD_VAL_4) as num from pt_total_count where RECORD_TYPE=2001 AND RECORD_VAL_1=0";
        $res = self::$mysql->doSql($sql);
        if(!empty($res)) {
//            addlog('monitor/robot/robot','robot_error:超出数量为：'.$num.'(元为单位) 数据异常'."\n",1);
            $num = $res[0]['num'];
            echo $num."\n";
            if($num>2000000){
                addlog('monitor/robot/robot','robot_error:机器人（所有子游戏）输：'.$num/100 .'(元为单位) 数据异常'."\n",1);
                $msg = "验证码是:jqr". $num/100 .'(yuan)'.date('Y-m-d');
                $s = self::isSendMsg('php_robot');
                if($s == false){
                    sendMsg($msg);
                    self::$redis->set('php_robot',1,3600);
                }
            }
        }
        unset($res);
    }
    /*统计每小时各子游戏对局的局数，和每个子游戏产生的服务费*/
    public static function Statistics_service_gamerecord(){
        //跑所有数据
//        $time = 1572578338;//开服时间戳
//        for($i=1;$i<=80;$i++){
//            //...
//            $time +=3600;
//        }
        $time = time()-3600;
        $h = date('H',$time);
        $day = date('Y-m-d',$time);
        $day1 = date('Y-m-d H:i:s',$time);
        $day2 = date('YmdH',$time);
//        echo $h.'------'.$day.'-----'.$day1.'-----'.$day2;exit;
        //计算当前小时的时间区间
        $start = strtotime($day) + intval($h) * 3600;
        $end = $start + 3600;
        $db = date('Y_m_d',$time);
        //测试
        $hmap = ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
        //对局记录
        $cmd = [
            'aggregate' => 'gamecoinlog',
            'pipeline' => [
                ['$match' => [ 'time_stamp' => ['$gte'=>$start,'$lt'=>$end] ]],
                ['$group' => [  '_id' => ['gameid'=>'$gameid'],'count' => ['$sum'=>1], 'sum_num'=>['$sum'=>'$num']] ],
            ],
            'cursor' => new \stdClass,
        ];
        $res = self::$mongo->command($db,$cmd);
        $data = [];
        foreach($res as $v){
            $data[] = ['game'=>$v->_id->gameid,'game_record_count'=>$v->count,'player_win_lose'=>$v->sum_num];
        }
        //彩池
        $data2 = [];
        $cmd = [
            'aggregate' => 'hitcolorpoolcoinlog',
            'pipeline' => [
                ['$match' => [ 'time_stamp' => ['$gte'=>$start,'$lt'=>$end] ]],
                ['$group' => [  '_id' => ['gameid'=>'$gameid'],'sum_num'=>['$sum'=>'$num'] ] ],
            ],
            'cursor' => new \stdClass,
        ];
        $res = self::$mongo->command($db,$cmd);
        foreach($res as $j){
            $game_id = $j->_id->gameid;
            $game_color_sum = $j->sum_num ;
            $data2[$game_id] =$game_color_sum;
        }
        //服务费
        $data1 = [];
        $cmd = [
            'aggregate' => 'servicechargelog_'.$h,
            'pipeline' => [
                ['$group' => [  '_id' => ['gameid'=>'$gameid'],'servicecharge_count' => ['$sum'=>'$service_charge'] ] ],
            ],
            'cursor' => new \stdClass,
        ];
        $res = self::$mongo->command($db,$cmd);
        foreach($res as $j){
            $game_id = $j->_id->gameid;
            $fee = $j->servicecharge_count ;
            if(isset($data1[$game_id])){
                $data1[$game_id] +=$fee;
            }else{
                $data1[$game_id] =$fee;
            }
        }
        foreach($data as $k => $v){//对局记录 [['game'=>20,'game_record_count'=>20,'player_win_lose'=>-300]]
            if(isset($data1[$v['game']])){
                $data[$k]['servicecharge'] = $data1[$v['game']];
            }else{
                $data[$k]['servicecharge'] = 0 ;
            }
            if(isset($data2[$v['game']])){
                $data[$k]['game_color_sum'] = $data2[$v['game']];
            }else{
                $data[$k]['game_color_sum'] = 0;
            }
        }
//入库
        $date = $day2;
        $total_game_record = 0;
        $total_service = 0;
        $total_player_win_lose = 0;
        $total_game_color_sum = 0;
        foreach($data as $v){
            $gameid = $v['game'];//游戏ID
            $gamerecord = intval($v['game_record_count']);//该游戏对局数
            $total_game_record += $gamerecord;//所有游戏总对局数
            $service_charge = $v['servicecharge'];//该游戏服务费
            $total_service += $service_charge;//所有游戏总服务费
            $player_win_lose = $v['player_win_lose'];//该游戏玩家净输赢
            $total_player_win_lose += $player_win_lose;//所有游戏玩家净输赢
            $game_color_sum = $v['game_color_sum'];//该游戏的彩池数
            $total_game_color_sum +=$game_color_sum;//所有游戏的彩池数
//           RECORD_TYPE = 100
            //按天和子游戏类型统计服务费和对局数
            $sql = "select * from db_detail.pt_total_count where RECORD_TYPE=2001 and RECORD_TIME='$date' and RECORD_VAL_1=$gameid";
            $res = self::$mysql->doSql($sql);
            if(!empty($res)){
                $sql = "update  db_detail.pt_total_count set RECORD_VAL_2='$gamerecord',RECORD_VAL_3='$service_charge',RECORD_VAL_4='$player_win_lose' ,RECORD_VAL_5='$game_color_sum',REG_TIME='$day1' where RECORD_TYPE=2001 and RECORD_TIME='$date' and RECORD_VAL_1=$gameid";
                self::$mysql->doSql($sql);
            }else{
                $sql = "insert into db_detail.pt_total_count (ID,RECORD_TYPE,RECORD_TIME,RECORD_VAL_1,RECORD_VAL_2,RECORD_VAL_3,RECORD_VAL_4,RECORD_VAL_5,REG_TIME) values(null,2001,'$date',$gameid,'$gamerecord','$service_charge',$player_win_lose,$game_color_sum,'$day1')";
                self::$mysql->doSql($sql);
            }
        }
        if(!empty($data)){
            //按天统计所有子游戏的服务费和对局数和玩家净输赢
            $sql = "select * from db_detail.pt_total_count where RECORD_TYPE=2001 and RECORD_TIME='$date' and RECORD_VAL_1=0";
            $res = self::$mysql->doSql($sql);
            if(!empty($res)){
                $sql = "update  db_detail.pt_total_count set RECORD_VAL_2='$total_game_record',RECORD_VAL_3='$total_service',RECORD_VAL_4='$total_player_win_lose',RECORD_VAL_5='$total_game_color_sum', REG_TIME='$day1' where RECORD_TYPE=2001 and RECORD_TIME='$date' and RECORD_VAL_1=0";
                self::$mysql->doSql($sql);
            }else{
                $sql = "insert into db_detail.pt_total_count (ID,RECORD_TYPE,RECORD_TIME,RECORD_VAL_1,RECORD_VAL_2,RECORD_VAL_3,RECORD_VAL_4,RECORD_VAL_5,REG_TIME) values(null,2001,'$date',0,'$total_game_record','$total_service',$total_player_win_lose,$total_game_color_sum,'$day1')";
                self::$mysql->doSql($sql);
            }
        }
    }
    /* 统计玩家金币来源和金币出向 */
    public static function coin_in_out(){
        $map = [];
//当日充值
        //ss
        $start = date('Y-m-d H:i:s',strtotime(date('Y-m-d'))-24*3600);
        $end = date('Y-m-d H:i:s',strtotime(date('Y-m-d'))+24*60*60);
        $sql = "select sum(amount)as recharge,user_id as rid from pt_order where status=3 and create_time between '$start' and '$end' group by user_id";
        $res= self::$mysql->doSql($sql);
//$res = [
//    ['rid'=>2000625,'recharge'=>199],
//    ['rid'=>1002254,'recharge'=>499]
//];
        self::merge_info($map,$res);
//当日被赠送
        $sql = "select sum(MONEY)as gived,INCOME_ID as rid from pt_give_friend_record where STATUS=2 and REG_TIME between '$start' and '$end' group by INCOME_ID";
        $res= self::$mysql->doSql($sql);
//$res = [
//    ['rid'=>2000625,'gived'=>20]
//];
        self::merge_info($map,$res);
//当日收益提现(五级返利加大区收益)
        $sql = "select sum(COIN)as agent_income, AGENT_ID as rid from pt_coin_distribute where STATUS=3 and DIST_TYPE in (2,3) and DIST_TIME between '$start' and '$end' group by AGENT_ID";
        $res= self::$mysql->doSql($sql);
//$res = [
//    ['rid'=>2000625,'agent_income'=>90]
//];
        self::merge_info($map,$res);
//系统加金币
        $sql = "select sum(COIN)as system_income, AGENT_ID as rid from pt_coin_distribute where STATUS=3 and DIST_TYPE in (1,4,11,12) and DIST_TIME between '$start' and '$end' group by AGENT_ID";
        $res= self::$mysql->doSql($sql);
//$res = [
//    ['rid'=>2000625,'system_income'=>20]
//];
//var_dump($res);exit;
        self::merge_info($map,$res);
//游戏结算赢
        $db = date('Y_m_d',strtotime("-1 day"));
        $cmd_win = [
            'aggregate' => 'gamecoinlog',
            'pipeline' => [
                ['$match' => [ 'num' => ['$gte'=>0] ]],
                ['$group' => [ '_id' => ['rid'=>'$rid'],'game_win'=>['$sum'=>'$num']]],
            ],
            'cursor' => new \stdClass,
        ];
        $res = self::$mongo->command($db,$cmd_win);
        $data = [];
        foreach($res as $v){
            $data[] = ['rid'=>$v->_id->rid,'game_win'=>$v->game_win];
        }
//$data = [
//    ['rid'=>2000625,'game_win'=>-1000]
//];
//var_dump($data);exit;
        self::merge_info($map,$data);
//下发
        $start1 = strtotime(date('Y-m-d')) - 24*3600;
        $end1 = $start1 + 24*60*60;
        $sql = "select sum(with_coin)/100 as cash_out,rid from game_1.role_with where agree_action=2 and apply_time between $start1 and $end1 group by rid";
        $res= self::$mysql->doSql($sql);
//$res = [
//    ['rid'=>2000625,'cash_out'=>300]
//];
        self::merge_info($map,$res);
//赠送
        $sql = "select sum(MONEY)as give,USER_ID as rid from pt_give_friend_record where STATUS=2 and REG_TIME between '$start' and '$end' group by USER_ID";
        $res= self::$mysql->doSql($sql);
//$res = [
//    ['rid'=>2000625,'give'=>30],
//    ['rid'=>2004937,'give'=>30],
//];
        self::merge_info($map,$res);
//系统减金币
        $sql = "select sum(COIN)as system_reduce, AGENT_ID as rid from pt_coin_distribute where STATUS=3 and DIST_TYPE =10 and DIST_TIME between '$start' and '$end' group by AGENT_ID";
        $res= self::$mysql->doSql($sql);
//$res = [
//    ['rid'=>2000625,'system_reduce'=>20]
//];
        self::merge_info($map,$res);
//游戏输
//$db = date('Y_m_d');
        $cmd_lose = [
            'aggregate' => 'gamecoinlog',
            'pipeline' => [
                ['$match' => [ 'num' => ['$lt'=>0] ]],
                ['$group' => [ '_id' => ['rid'=>'$rid'],'game_lose'=>['$sum'=>'$num']]],
            ],
            'cursor' => new \stdClass,
        ];
        $res = self::$mongo->command($db,$cmd_lose);
        $data = [];
        foreach($res as $v){
            $data[] = ['rid'=>$v->_id->rid,'game_lose'=>$v->game_lose];
        }
//$data = [
//    ['rid'=>2000625,'game_lose'=>-1000]
//];
        self::merge_info($map,$data);
//字段补齐
        $filed_map = ['date','recharge','gived','agent_income','system_income','game_win','cash_out','give','system_reduce','game_lose'];
        foreach($map as $k =>$v){
            foreach($filed_map as $f){
                if(!isset($v[$f])){
                    if($f == 'date'){
                        $map[$k][$f] = date('Y-m-d',strtotime("-1 day"));
                    }else{
                        $map[$k][$f] = 0;
                    }
                }
            }
        }
        $coll = 'plat.coin_in_out';
        if(!empty($map)){
            self::$mongo->add($coll, $map);
        }
    }
    public static function merge_info(&$map,$data){
        foreach($data as $k=>$v){
            if(isset($map[$v['rid']])){
                $map[$v['rid']] = array_merge($map[$v['rid']],$v);
            }else{
                $map[$v['rid']] = $v;
            }
        }
    }
    /*统计每天下发大于10万的收款账号*/
    public static function xiafa(){
        $num = 100000;//每日下发报警线（元为单位）
        $ymd = date('Y-m-d');
        $start = strtotime(date('Y-m-d')) - 24*3600;//统计前一天
        $end = $start + 24*3600;
        $sql ="select sum(with_coin)/100 as s_with_coin , with_code, rid, memo from game_1.role_with where agree_action=2 and apply_time between $start and $end group by with_code having s_with_coin>$num";
        $res = self::$mysql->doSql($sql);
        if(!empty($res)){
            addlog('monitor/xiafa_more_10w/'.$ymd,json_encode($res),0);
        }
    }
    /*统计玩家胜率，输赢值*/
    public static function rate_profit(){
        $db = date('Y_m_d',strtotime("-1 day"));//统计前一天
//        $db = date('Y_m_d',time());
//        $db = '2019_07_21';
        //赢
        $cmd_win = [
            'aggregate' => 'gamecoinlog',
            'pipeline' => [
                ['$match' => [ 'num' => ['$gte'=>0] ]],
                ['$group' => [ '_id' => ['rid'=>'$rid'], 'count' => ['$sum'=>1] ,'sum_num'=>['$sum'=>'$num']] ],
            ],
            'cursor' => new \stdClass,
        ];
        $res_win = self::$mongo->command($db,$cmd_win);
        $data_win = [];
        foreach($res_win as $v){
            $data_win[$v->_id->rid] = ['win_num'=>$v->count,'win_money'=>$v->sum_num];
        }
        //输
        $cmd_lose = [
            'aggregate' => 'gamecoinlog',
            'pipeline' => [
                ['$match' => [ 'num' => ['$lt'=>0] ]],
                ['$group' => [ '_id' => ['rid'=>'$rid'], 'count' => ['$sum'=>1] ,'sum_num'=>['$sum'=>'$num']] ],
            ],
            'cursor' => new \stdClass,
        ];
        $res_lose = self::$mongo->command($db,$cmd_lose);
        $data_lose = [];
        foreach($res_lose as $v){
            $data_lose[$v->_id->rid] = ['lose_num'=>$v->count,'lose_money'=>$v->sum_num];
        }
        $data = [];
        foreach($data_win as $k=>$v){
            $lose_num = isset($data_lose[$k])?$data_lose[$k]['lose_num']:0;
            $lose_money = isset($data_lose[$k])?$data_lose[$k]['lose_money']:0;
            $win_rate = $v['win_num']/($v['win_num']+$lose_num);
            $profit = $lose_money+$v['win_money'];
            $data[$profit] = ['date'=>$db,'rid'=>$k,'win_rate'=>$win_rate,'profit'=>$profit,'win_num'=>$v['win_num'],'win_money'=>$v['win_money'],'lose_num'=>$lose_num,'lose_money'=>$lose_money];
        }
        krsort($data);
        $data = array_values($data);
        if(!empty($data)){
            addlog('monitor/player_rate_win/'.$db,json_encode($data),0);
        }
//        var_dump($data);
    }
    /*统计金币变动大于5W的操作（仅游戏结算）*/
    public static function win_more_five(){
        $db = date('Y_m_d',strtotime("-1 day"));
        $collection = $db . '.gamecoinlog';
        $filter = [
//            'time_stamp'=>['$mod'=>[2,$mod]],//对5取模
            'status' => ['$gte' => 5000000]
        ];
        $options = [
            'projection' => ['beforetotal'=>1,'num'=>1,'aftertotal'=>1,'game_id','key'=>1,'rid'=>1,'reason'=>1], //选需要的字段，id是默认的
        ];
        $res = self::$mongo->query($collection, $filter, $options);
        if(!empty($res)){
            addlog('monitor/coinlog_more_10w/'.$db,json_encode($res),0);
        }
    }
    /*统计半小时内 玩家赢钱超过3w*/
    public static function minute_30(){
//        $db = '2019_06_10';
        $db = date('Y_m_d',strtotime("-1 day"));
        $collection = $db.'.gamecoinlog';
        $filter = [
            'num' => ['$gte'=>0]//赢
        ];
        $options = [
            'projection' => ['beforetotal'=>1,'num'=>1,'aftertotal'=>1,'key'=>1,'rid'=>1,'reason'=>1,'time_stamp'=>1], //选需要的字段，id是默认的
        ];
        $mongo = new MongoService();
        $res = $mongo->query($collection, $filter, $options);
        $time = 30*60;//时间间隔
        $money = 3000000;//报警金币线
        $map = [];
        $user_map = [];
        foreach($res as $v){
            $rid = $v['rid'];
            $num = $v['num'];
            $time_stamp = $v['time_stamp'];
            if(isset($map[$rid]) && !empty($map[$rid]['time_list'])){
                //当前时间节点距time_list栈的起始时间点大于半个小时,循环处理使time_list中的时间距离小于规定的时间间隔
                if($time_stamp - $map[$rid]['time_list'][0] > $time){
                    foreach($map[$rid]['time_list'] as  $i =>$j){
                        if($time_stamp - $j >$time){
                            $map[$rid]['total'] =  $map[$rid]['total'] - $map[$rid]['num_list'][$i];
                            unset($map[$rid]['num_list'][$i]);
                            unset($map[$rid]['time_list'][$i]);
                        }
                    }
                }
                //将 当前时间节点的时间和num添加到num_list和time_list,并将当前num累加在total中
                $map[$rid]['total'] +=$num;
                $map[$rid]['num_list'][] = $num;
                $map[$rid]['time_list'][] = $time_stamp;
                if(count($map[$rid]['time_list']) == 1){//当某个时间点的num就已经超过了报警金额就直接将该用户放入user_map
                    if($map[$rid]['total'] >=$money){
                        if(in_array($rid,$user_map)){//之前已经报过警
                        }else{
                            $user_map[] = $rid;
                        }
                    }
                    $map[$rid]['total'] =0;
                    $map[$rid]['num_list'] = [];
                    $map[$rid]['time_list'] = [];
                }else{
                    if($map[$rid]['total'] >= $money){
                        if(in_array($rid,$user_map)){//之前已经报过警
                        }else{
                            $user_map[] = $rid;
                        }
                        foreach($map[$rid]['num_list'] as $i=>$j){
                            if($map[$rid]['total'] - $map[$rid]['num_list'][$i] <$money){
                                $map[$rid]['total'] =  $map[$rid]['total'] - $map[$rid]['num_list'][$i];
//                            array_splice($map[$rid]['num_list'],0,1);
//                            array_splice($map[$rid]['time_list'],0,1);
                                unset($map[$rid]['num_list'][$i]);
                                unset($map[$rid]['time_list'][$i]);
                            }
                        }
                    }
                }
            }else{
                $map[$rid] = [
                    'total'=>$num,
                    'num_list'=>[$num],
                    'time_list'=>[$time_stamp]
                ];
                if($num >= $money){
                    if(in_array($rid,$user_map)){//之前已经报过警
                    }else{
                        $user_map[] = $rid;
                    }
                }
            }
            $map[$rid]['num_list'] = array_values($map[$rid]['num_list']);
            $map[$rid]['time_list'] = array_values($map[$rid]['time_list']);
        }
        if(!empty($user_map)){
            addlog('monitor/minute30/'.$db,json_encode($user_map),0);//请求GM 添加日志
        }
    }
    public static function isSendMsg($type){
        $s = self::$redis->get($type);
        return $s;
    }
    /*下发超过三次*/
    public static function xiafa3(){
        $ymd = date('Y-m-d');
        $start = strtotime(date('Y-m-d')) - 24*3600;//统计前一天
        $end = $start + 24*3600;
        $sql = "select count(*) as c_num,sum(with_coin)as s_num,rid from game_1.role_with  where agree_action=2 and apply_time BETWEEN $start and $end group by rid having c_num>=3 order by c_num desc;";
        $res = self::$mysql->doSql($sql);
        if(!empty($res)){
            addlog('monitor/xiafa_more_10w/'.$ymd,json_encode($res),0);
        }
    }

    /*活动发放（龙虎102和推筒子114坐庄is_banker=true奖励）*/
    public static function huodong_zuozhuang(){
        //活动现时11/11  - 11/13
        $db = date('Y_m_d',strtotime("-1 day"));
//        $db = date('Y_m_d',time());
        $m = date('m',strtotime("-1 day"));
        $d = date('d',strtotime("-1 day"));
        if(in_array($db,['2019_11_11','2019_11_12','2019_11_13'])){
            //gamecoinlog_activity
            $cmd_lose = [
                'aggregate' => 'gamecoinlog_activity',
                'pipeline' => [
                    ['$match' => [ 'is_banker' => ['$eq'=>'true'] ,'total_bet_score'=>['$gte'=>5000]]],
                    ['$group' => [ '_id' => ['rid'=>'$rid'], 'count' => ['$sum'=>1]] ],
                    ['$match'=>['count'=>['$gte'=>10]]]
                ],
                'cursor' => new \stdClass,
            ];
            //得到至少坐庄10次的玩家
            $zz = self::$mongo->command($db,$cmd_lose);

            foreach($zz as $v){
                $rid = $v->_id->rid;
                $count = $v->count;
                $res = self::getZuozhuang($count);
                if($res == 0){
                    continue;
                }

                $coin = $res['coin'];
                $level = $res['level'];
                $body = [
                    'cmd'=>'send_mail',
                    "rid"=>$rid * 1,
                    'reason'=>46,
                    'content'=>[
                        'title'=>'百人场坐庄奖励',
                        'des'=>"尊敬的忠实用户您好，感谢您对博胜娱乐的支持!您在".$m."月".$d."日达到坐庄".$level."次，奖励".$coin."，已到账，请查收！",
                        'isattach'=>true,
                        'awards'=>[
                            [
                                'id'=>1,
                                'num'=>$coin * 100]
                        ]
                    ]

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
                $pt_game['response'] = $res;
                addlog('/activity/zuozhuang_pt_game'.$db,json_encode($pt_game));//平台回调游戏日志
                //添加到发放记录
                $coin_mysql = $coin * 100;
                $time = date('Y-m-d H:i:s',time());
                $sql = "insert into db_detail.pt_coin_distribute values(null,$rid,23,$coin_mysql,3,'$time')";
                self::$mysql->doSql($sql);
            }

        }




    }

    public static function getZuozhuang($count){
        if(10<=$count && $count <20){
            return ['level'=>10,'coin'=>88];
        }else if(20 <=$count && $count <50){
            return ['level'=>20,'coin'=>188];
        }else if($count >= 50){
            return ['level'=>50,'coin'=>588];
        }
//        if(3<=$count && $count <4){
//            return ['level'=>3,'coin'=>88];
//        }else if(4 <=$count && $count <7){
//            return ['level'=>4,'coin'=>188];
//        }else if($count >= 10){
//            return ['level'=>10,'coin'=>588];
//        }

        return 0;
    }
}