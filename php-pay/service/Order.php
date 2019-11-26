<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/12
 * Time: 10:54
 */

class Order{
    //平台生成订单
    public static function createOrder($data){

        //参数rid=2001248&paytype=guoxin_alipay&goods_id=1&md5
        //根据goods_id去pt_goods表中查找PRICE（分为单位）,为玩家充值金额，最后调GM加金币为AWARD
        //参数效验

        if(!isset($data['rid']) || !isset($data['paytype']) || !isset($data['goods_id']) || !isset($data['key'])|| !isset($data['time'])){
            return ['error'=>'param is error'];
        }

//        if($data['rid'] != 2082466){
//
//            return ['error'=>'充值通道暂未开启！'];
//        }
        //防频繁请求
        $key =  md5($data['rid'].$data['paytype'].$data['goods_id'].$data['time'].'hwiiouyjds');
        if($key !=$data['key']){
//            return ['error'=>'illegal request'];
        }
        $redis = new Redis();
        $redis->connect(config('redis_host'),config('redis_port'),2);

        if(config('redis_pwd') != ''){
            $redis->auth(config('redis_pwd'));
        }
        if(config('redis_db') != ''){
            $redis->select(config('redis_db'));
        }
        $rs = $redis->get('orderkey_'.$key);
        if($rs == '1'){
//            return ['error'=>'request frequently'];
        }else{
            $redis->set('orderkey_'.$key,1,300);//相同参数 限制请求间隔时间
        }
        //根据goods_id查询商品金额
        $pdo = new mysql(['dbname'=>'db_jingcai']);
        $goods_id= $data['goods_id'];
        $sql = "select * from pt_goods where id=$goods_id";

        $row = $pdo->doSql($sql);

        if(empty($row)){
            return ['error'=>'goods is not exist'];
        }
        $price = $row[0]['price'];//玩家支付金额
        $award = $row[0]['award'];//玩家上分金额
        //查询玩家ID是否存在
        $player_id = $data['rid'];
        $sql = "select * from db_detail.pt_agent where rid=$player_id";
        $res = $pdo->doSql($sql);
        if(empty($res)){
            return ['error'=>'rid is not exist'];
        }
        //生成订单
        $price = sprintf("%.2f",  intval($price));//金额 转换成两位小数
//        $award= sprintf("%.2f",  intval($award)/100);//金额 转换成两位小数
        $pay_type = $data['paytype'];
        //在db_detail.pt_order中创建订单
//        Db::$instance = null;
//        $db = Db::getInstance('db_detail');
        $order_id = orderno();//订单号
        $create_time = date('Y-m-d H:i:s',time());
        $sql = "insert into pt_order_shop values('$order_id',$goods_id,$player_id,$price,$award,'$pay_type','','$create_time','$create_time','$create_time',1,0)";
//        echo $sql;exit;
        $res =$pdo->doSql($sql);
        if(!$res){
            return ['error'=>'create order error'];
        }

        return ['order_id'=>$order_id,'price'=>$price,'rid'=>$player_id];

    }

    //组装支付表单放进redis
    public static function createpayform($order_id,$url,$form_data){
        $pay_key = md5($order_id);
        $redis_data = [
            'reqUrl'=>$url,//三方支付url
            'params'=>$form_data//表单信息
        ];

        $pay_url = PHP_CLIENT_URL.'/api/post_html?key='.$pay_key;//支付表单url
        //将支付表单加入redis，
        $redis = new Redis();
        $redis->connect(config('redis_host'),config('redis_port'),2);
        if(config('redis_pwd') != ''){
            $redis->auth(config('redis_pwd'));
        }
        if(config('redis_db') !== ''){
            $redis->select(config('rdis_db'));
        }

        $rs = $redis->set('PAY_CONTENT_PREFIX_'.$pay_key,json_encode($redis_data),500);
        if(!$rs){
            return ['error'=>'insert redis error '];
        }
        $out = [
            'ret'=>[
                'type'=>2,
                'url'=>$pay_url
            ],
            'state'=>1
        ];
        return $out;
    }

    //生成一个支付页面
    public static function createpayhtml($order_id,$url,$form_data,$name){
        $html = '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <title>Document</title>
                    </head>
                    <style>
                        
                        input,select,textarea{ margin-top: 15px; }
                    </style>
                    <body>';

        $html .= '<form action="'.$url.'" method="post" >';
        foreach($form_data as $k=>$v){
            $html .= '<input type="hidden" name="'.$k.'"  value="'.$v.'"/>';
        }
        $html.='</form>';
        $html .='</body>
                </html>';
        if(is_dir('../'.$name)){
            mkdir('../html/'.$name,0777,true);
        };
        $file_name = $order_id.'.html';
        file_put_contents($file_name,$html);
    }

    //平台更新订单
    public static function update($order_id,$status = 3){
	    Db::$instance = null;
        $db = Db::getInstance('db_jingcai');
        $sql = "update pt_order_shop set status = $status where id='$order_id'";
        $rs = $db->query($sql);
        return $rs;
    }

    //平台回调游戏
    public static function callback($order_id,$name,$pay_id=''){
	    Db::$instance = null;
        $db = Db::getInstance('db_jingcai');
        //pt_order.status: 0：待支付  1：支付成功，待回调  2:支付失败  3：回调成功支付完成
        $rs = $db->update("update pt_order_shop set status = 1,pay_id='$pay_id' where id = '$order_id' and status = 0");
        if(!$rs){
            exit;//订单不存在或者多次回调 直接退出
        }
        $sql = "select user_id,status,order_id,award from pt_order_shop where order_id='$order_id'";
        $row = $db->getRow($sql);
        if(empty($row)){
            exit;
        }

        $body = [
	        "cmd"=>"order_supplement",
            "rid"=>intval($row['user_id']),
            'order_id'=>$row['order_id'],
            'coin'=>intval($row['award'])
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
        addlog($name.'/pt_game',json_encode($pt_game));//平台回调游戏日志
        if(isset($res['status']) && $res['status'] == 'success'){
            //回调成功修改订单状态
            $rs =Order::update($order_id);
        }else{
            //回调失败在db_system.pt_transactional_retry中添加纪录
            $rs =Order::callbackfalsedo($order_id,$row['user_id'],$row['award']);
            if(isset($rs['error'])){
                addlog($name.'/other_pt',$rs['error']);
            }
        }

    }

    //平台回调游戏失败 处理
    public static function callbackfalsedo($order_id,$rid,$coin){
        $content = [
            "cmd"=>"order_supplement",
            'rid'=>intval($rid),
            'coin'=>intval($coin),
            'order_id'=>$order_id
        ];
        $content = json_encode($content);
        $datetime = date('Y-m-d H:i:s',time());
        Db::$instance = null;
        $db = Db::getInstance('db_system');
        $sql = "insert into pt_transactional_retry (TRANS_TYPE,RETRY_TIME,CREATE_TIME,CONTENT) values(1,'$datetime','$datetime','$content')";
        $rs = $db->query($sql);
        if(!$rs){
            return ['error'=>'添加表pt_transactional_retry失败'];
        }
        return true;
    }
}

