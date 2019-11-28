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
    public static function onConnect($client_id)
    {

    }
    public static function onMessage($client_id, $messages)
    {

    }
    public static function onWorkerStart($businessWorker)
    {
//        require_once dirname(dirname(dirname(__FILE__))).'/service/MongoService.php';
//        $mongo = new MongoService();
//        self::$mongo = $mongo;
//        require_once dirname(dirname(dirname(__FILE__))).'/service/MysqlService.php';
        //alipay SDK
//        require 'service/lib.php';
//        require 'service/aop/SignData.php';
//        require 'service/aop/AopClient.php';
//        require 'service/aop/AlipayFundTransToaccountTransferRequest.php';//
//        require 'service/aop/AlipayFundTransToaccountTransferRequest.php';//
//        require 'service/aop/AlipayFundTransOrderQueryRequest.php';//查询订单
//        self::$aop = new AopClient ();
//        self::$aop_request_create = new AlipayFundTransToaccountTransferRequest ();
//        self::$aop_request_query = new AlipayFundTransOrderQueryRequest ();
//        self::$mysql = new MysqlService(['dbname'=>'db_detail']);
        // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
        if ($businessWorker->id === 0)
        {
            $db=13;
            $h=12;
            $str = '{"a":"b"}'."\n<br/>";
            $str .='{"c":"d"}';
            sendEmail('record'.$db.'_'.$h,$str);
//            self::xiafa20();
            // 定时器1
            Timer::add(30,function(){
                self::monitorCoinChange();
            });
            //监控保险箱金币
        }else if($businessWorker->id === 1){

            Timer::add(30,function(){
//                self::monitorBank();
            });

        }else if($businessWorker->id === 2){
            //监控代理收益
            Timer::add(50,function(){
//                self::monitorAgentFee();
            });

        }else if($businessWorker->id === 3){
            //监控机器人输赢
            Timer::add(30,function(){
//                self::monitorRobot();
            });
        }

    }

    /*
     * 轮询处理game_1.role_with中的下发订单，调用支付宝 企业=》个人转账接口 创建支付宝转账订单
     * */
    public static function createOrder(){
        //1.查询role_with中状态为0的订单
        $mysql = new MysqlService(['dbname'=>'game_1']);
        $sql = "select rid,apply_time,with_id,with_code,with_coin,memo,with_type from game_1.role_with where agree_action = 0";
        $res = $mysql->doSql($sql);
        if(!empty($res)){
            foreach($res as $k=>$v){
                //根据权重选择一个支付宝
                $alipaynumber = self::choiceAliNumber();
                $aop = new AopClient ();
                $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
                $aop->appId = $alipaynumber['APP_ID'];//appid
                $aop->rsaPrivateKey = trim($alipaynumber['APP_PRIVATE_KEY']);
                $aop->alipayrsaPublicKey=trim($alipaynumber['ALIPAY_PUBLICK_KEY']);
                $aop->apiVersion = '1.0';
                $aop->signType = 'RSA2';
                $aop->postCharset='UTF-8';
                $aop->format='json';
                $request = new AlipayFundTransToaccountTransferRequest ();
                $out_biz_no=$res['rid'].'order'.$res['apply_time'];
                $payee_account = $res['with_code'];
                $amount = $res['with_coin']/100;
                $payee_real_name = $res['memo'];
                $request->setBizContent("{" .
                    "\"out_biz_no\":\"$out_biz_no\"," .
                    "\"payee_type\":\"ALIPAY_LOGONID\"," .
                    "\"payee_account\":\"$payee_account\"," .
                    "\"amount\":\"$amount\"," .
                    "\"payer_show_name\":\"私人企业\"," .
                    "\"payee_real_name\":\"$payee_real_name\"," .
                    "\"remark\":\"个人转账\"" .
                    "  }");
                $result = $aop->execute ( $request);
                //处理网络超时

                $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
                $resultCode = $result->$responseNode->code;
                //提交转账申请成功
                if(!empty($resultCode)&&$resultCode == 10000){
                    $ali_order_id = $result->$responseNode->order_id;
                    $eng_name = $alipaynumber['ENG_NAME'];
                    //更改支付宝方的订单id到role_with的with_memo字段中，查询定时任务会用到该订单号，以及订单状态为1：已提交成功，等待支付宝方处理，以及下分渠道
                    $sql = "update role_with set with_memo = $ali_order_id ,agree_action = 1,with_channel='".$eng_name."' where with_id=".$v['with_id'];
                    self::$mysql->doSql($sql);
                } else {
                    //更改下发订单状态 agree_action=3
                    $sql = "update role_with set agree_action=3 where with_id=".$v['with_id'];
                    self::$mysql->doSql($sql);
                    //调用GM命令退还金币
                    $body = [
                        "cmd"=>"with_add_coin",
                        "rid"=>$v['rid'],
                        'with_id'=>$v['with_id']
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
                    $logname = date('Y-m-d',time()).'-return_coins';
                    addlog('xiafa/'.$logname,json_encode($body));//请求GM 添加日志
                    $res = curl_post($callback_url,$body,$header,true);
                    addlog('xiafa/'.$logname,json_encode($res));//GM响应 添加日志
                    if($res['status'] == "success"){
                        continue;
                    }else{
                        //加入重试队列
                        $body = ['rid'=>$v['rid'],'withId'=>$v['with_id']];
                        $content = json_encode($body);
                        $datetime = date('Y-m-d H:i:s',time());
                        $sql = "insert into db_system.pt_transactional_retry (TRANS_TYPE,RETRY_TIME,CREATE_TIME,CONTENT) values(7,'$datetime','$datetime','".$content."')";
                        self::$mysql ->doSql($sql);
                    }

                }
            }
        }
    }

    /*
     * 根据权重选择支付宝账号
     * */
    public static function choiceAliNumber(){
        //查询出所有的支付宝通道
        $sql = $sql = "select 
              a.ENG_NAME,a.CHANNEL_WEIGHT,a.WITH_TYPE,b.APP_NAME,b.APP_ID,b.APP_PRIVATE_KEY,b.ALIPAY_PUBLICK_KEY
            from db_detail.pt_with_channel as a 
            join db_detail.pt_alipay_msg as b 
            on  a.ENG_NAME=b.APP_NAME
            where WITH_TYPE=1 and a.CHANNEL_WEIGHT > 0";
        $all_channel = self::$mysql->doSql($sql);
        //根据权重选择一个支付宝
        $weight = 0;
        $data = array();
        foreach ($all_channel as $one) {
            $oneWeight = (int)$one['CHANNEL_WEIGHT'] ? $one['CHANNEL_WEIGHT'] : 1;
            $weight += $oneWeight;
            for ($i = 0; $i < $oneWeight; $i ++) {
                $data[] = $one;
            }
        }
        return $data[rand(0, $weight-1)];
    }

    /*
     * 轮询获取支付宝方的订单状态
     * */
    public static function queryOrder(){
        //1.查询role_with中状态为1的订单，并查询支付宝订单查询接口
        //agree_action: 1:下发中 2：下发成功 3：返回中 4：返回成功 5：重试下发中 6：查询中
        $mysql = new MysqlService(['dbname'=>'game_1']);
        $sql = "select rid,apply_time,with_id,with_code,with_coin,memo,with_type,with_memo from game_1.role_with where agree_action = 1";
        $res = $mysql->doSql($sql);
        foreach($res as $v){
            //查询该订单的下发支付宝通道信息
            $alipaynumber = self::$mysql->doSql("select APP_ID,APP_PRIVATE_KEY,APP_PUBLICK_KEY from pt_alipay_msg where APP_NAME=".$v['ENG_NAME']);
            $aop = new AopClient ();
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
            $aop->appId = $alipaynumber['APP_ID'];
            $aop->rsaPrivateKey = trim($alipaynumber['APP_PRIVATE_KEY']);
            $aop->alipayrsaPublicKey=trim($alipaynumber['APP_PUBLICK_KEY']);
            $aop->apiVersion = '1.0';
            $aop->signType = 'RSA2';
            $aop->postCharset='UTF-8';
            $aop->format='json';
            $request = new AlipayFundTransOrderQueryRequest ();
            $out_biz_no = $res['rid'].'order'.$res['apply_time'];
            $order_id = $v['with_memo'];
            $request->setBizContent("{" .
                "\"out_biz_no\":\"$out_biz_no\"," .
                "\"order_id\":\"$order_id\"" .
                "  }");
            $result = $aop->execute ($request);
            $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
            $resultCode = $result->$responseNode;
//            var_dump($resultCode);
            if(!empty($resultCode)&&$resultCode == 10000){
                //更改订单状态为2
                $sql = "update role_with set agree_action = 2 where with_id=".$v['with_id'];
                self::$mysql->doSql($sql);
                echo "成功";
            } else {
                //更改状态为3
                echo "失败";
                $sql = "update role_with set agree_action=3 where with_id=".$v['with_id'];
                self::$mysql->doSql($sql);
                //调用GM命令退还金币
                $body = [
                    "cmd"=>"with_add_coin",
                    "rid"=>$v['rid'],
                    'with_id'=>$v['with_id']
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
                $logname = date('Y-m-d',time()).'-return_coins';
                addlog('xiafa/'.$logname,json_encode($body));//请求GM 添加日志
                $res = curl_post($callback_url,$body,$header,true);
                addlog('xiafa/'.$logname,json_encode($res));//GM响应 添加日志
                if($res['status'] == "success"){
                    continue;
                }else{
                    //加入重试队列
                    $body = ['rid'=>$v['rid'],'withId'=>$v['with_id']];
                    $content = json_encode($body);
                    $datetime = date('Y-m-d H:i:s',time());
                    $sql = "insert into db_system.pt_transactional_retry (TRANS_TYPE,RETRY_TIME,CREATE_TIME,CONTENT) values(7,'$datetime','$datetime','".$content."')";
                    self::$mysql ->doSql($sql);
                }
            }
        }
    }

    /*
     * 处理重试任务 pt_transactional_retry
     * */
    public static function retry(){
        //pt_transactional_retry.TRANS_TYPE
        //6:提现失败重试
        $sql = "select * from db_system.pt_transactional_retry where STATE !=2 limit 5";
        $res = self::$mysql->doSql($sql);
        if(!empty($res)){
            foreach($res as $v){
                $body = [
                    "cmd"=>"with_add_coin",
                    "rid"=>$v['rid'],
                    'with_id'=>$v['with_id']
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
                $logname = date('Y-m-d',time()).'-return_coins';
                addlog('xiafa/'.$logname,json_encode($body));//请求GM 添加日志
                $res = curl_post($callback_url,$body,$header,true);
                if($res['status'] == 0){
                    $sql = "update db_system.pt_transactional_retry set STATE=3 , RETRY_NUM=RETRY_NUM+1";
                    self::$mysql->doSql($sql);
                }else{
                    $sql = "update db_system.pt_transactional_retry set STATE=2 , RETRY_NUM=RETRY_NUM+1";
                    self::$mysql->doSql($sql);
                }
            }
        }
    }




    /**
     * 新玩家新增入库（入平台库db_detail）
     */
    public static function worker_1()
    {
        $redis = RedisService::getInstance();
        $mysql_pt_detail = new MysqlService(['dbname'=>'db_detail']);
        if (RedisService::$status !== true)
        {
            die('redis服务出错' . RedisService::$status);
        }
        $list_len = $redis->lLen('platform');
        if($list_len > 50)
        {
            $list_len = 50;
        }
        $current_time = date("Y-m-d H:i:s",time());
        for ($i=0;$i<$list_len;$i++)
        {
            $re = $redis->lPop('platform');
            if($re != false)
            {
                $str = json_decode($re);
                $rid = $str->rid;
                //入库
                $sql = "insert into pt_player_extend values('{$rid}',1,0,'{$current_time}',null,0)";
                try{
                    $mysql_pt_detail->doSql($sql);
                }catch (Exception $e){

                }

            }
        }
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
               $str .= json_encode($v)."\n";
            }
        }
        if($str != ''){
            addlog('monitor/coinchange/record'.$db.'_'.$h,$str,0);//请求GM 添加日志
            sendEmail('record'.$db.'_'.$h,$str);
//            file_put_contents('/data/logs/monitor/record'.$db.'_'.$h.'.log',$str);
        }

        if($num != 0){
            addlog('monitor/coinchange/total'.$db.'_'.$h,'当前分片至少有 '.$num.' 数据异常'."\n",0);//请求GM 添加日志
//            file_put_contents('/data/logs/monitor/total'.$db.'_'.$h.'.log','当前分片至少有 '.$num.' 数据异常'."\n");//追加写入
            $msg = "数据异常报警：coin变动异常数量为".$num.'--时间：'.$db.'_'.$h;
            sendMsg($msg);

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
                $str .= json_encode($v)."\n";
            }
        }

        if($str != ''){
            addlog('monitor/bank/record_'.$db,$str,0);//请求GM 添加日志
//            file_put_contents('/data/logs/monitor/bank/record_'.$db.'.log',$str);
        }

        if($num != 0){
            echo $num;
            addlog('monitor/bank/total_'.$db,'当前分片至少有 '.$num.' 数据异常'."\n",0);
//            file_put_contents('/data/logs/monitor/total'.$db.'.log','当前分片至少有 '.$num.' 数据异常'."\n");//追加写入
            $msg = "数据异常报警-bankpack异常：异常数量为".$num.'--时间：'.$db;
            sendMsg($msg);
        }
        unset($data);
        unset($res);
    }

    /*
     * 监控代理收益总量小于服务费的二分之一
     * */
    public static function monitorAgentFee(){
        $ymd = date('Y_m_d');
        $h =date('H');
        $hh = getShareByhour($h);
        //计算当前分片的服务费
        $filter = [
            'flag'=>['$eq'=>1]
        ];
        $options = [
            'projection' => ['_id'=>1,'userId'=>1,'amount'=>1,'originId'=>1], //选需要的字段，id是默认的
        ];
        $collection = $ymd . '.t_deduct_' . $hh;
        $res = self::$mongo->query($collection,$filter,$options);

        $service_charge = 0;
        foreach($res as $v){
            $service_charge +=$v['amount'];
        }
        //计算当前分片的代理收益
        $filter = [
//            'flag'=>['$ne'=>1]
        ];
        $options = [
            'projection' => ['income'=>1], //选需要的字段，id是默认的
        ];
        $collection = $ymd . '.t_income_detail_' . $hh;
        $res = self::$mongo->query($collection,$filter,$options);
        $income = 0;
        foreach($res as $v){
            $income +=$v['income'];
        }
        //代理收益大于二分之一总服务费 就报警
        if($income > (0.5*$service_charge/100 + 50)){
            $num = $income * 100 -0.5*$service_charge;
            $msg = "数据异常报警-代理收益异常:超出数量为：".$num.'--分片：'.$ymd.'/'.$hh;
            sendMsg($msg);
        }
        unset($res);
    }

    /* 监控机器人输赢：每天不能超过2W */
    public static function monitorRobot(){
        $start = strtotime(date('Y-m-d'));
        $end = $start + 60*60*24;
        $start = date('Y-m-d H:i:s',$start);
        $end = date('Y-m-d H:i:s',$end);
        $sql = "select sum(ROBOT_NUM) + sum(ROBOT_FEE) as number  
                from pt_robot_datas 
                where CREATE_TIME 
                BETWEEN  '".$start."' AND '".$end."'";
        $res = self::$mysql->doSql($sql);
        if($res[0]['number'] < -20000 && !is_null($res[0]['number'])){
            $num =  $res[0]['number'] +20000;
            $msg = "数据异常报警-robot输赢异常:超出数量为：".$num.'--时间：'.date('Y-m-d');
            sendMsg($msg);
        }
        unset($res);
    }

    /*统计最近15天,每天下发金额前20名*/
    public static function xiafa20(){
        $file = './xiafa.log';
        $time = date('Y-m-d');
        $time =  strtotime($time);
        $num = 15;
        $str = '';
        for($i=1;$i<=$num;$i++){
            $start = $time - $i*24*60*60;
            $end = $start +24*60*60;
//    echo '前'.$i.'天：'.$start.'('.date('Y-m-d H:i:s',$start).') -----'.$end.'('.date('Y-m-d H:i:s',$end).')';
//    echo '<br/>';
            //with_code:银行卡号 或者支付宝账号  memo:姓名
            $sql = "select 
                sum(with_coin) as money ,with_code,memo,rid,with_time,agree_action 
                from game_1.role_with 
                where agree_action = 2 and with_time between $start and $end
                group by rid order by money desc limit 20";
            $res = self::$mysql->doSql($sql);
            $str .= date('Y-m-d H:i:s',$start).'------'.date('Y-m-d H:i:s',$end)."\n";
            foreach($res as $kk=>$vv){
               $vv['memo'] = urlencode($vv['memo']);
                $str .= urldecode(json_encode($vv))."\n";
            }
            $str .="\n";
        }
        file_put_contents($file,$str);
    }
}