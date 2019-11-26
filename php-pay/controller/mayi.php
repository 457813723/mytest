<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 16:15
 */
class mayi{
    const appid = '339477896';
    const key = '4c0d809df36c256c4de563e1c8a33382ae1c9808';
    const otherkey = '2b2d26a3d98f0c1382c5a632112914c2b513ce67';
    const url = 'http://a1.aspfreight.com/payway/gateway/';
    const name = 'mayi'; //日志目录 回调名
    //游戏请求支付
    public static function pay($m){
        $map = ['alipay'=>'alipc','wx'=>'wxwap'];  //--------------------
        $method = $map[$m];
        $res = Order::createOrder($_GET);//生成订单
        if(isset($res['error'])){
            echo json_encode(['state'=>-1,'msg'=>$res['error']]);exit;
        }

        $arr['app_id'] = self::appid;
        $arr['method'] = $method; //wxpc   wxwap    alipc   aliwap  只要一种方式对接成功即可
        $arr['buyer_id'] = $res['rid'];   //玩家ID
        $arr['trade_no'] = $res['order_id']; //订单号
        $arr['total_amount'] = $res['price'];  //金额
        $arr['return_notify'] = PHP_PAY_URL.'/'.self::name.'/asy.php';
        $arr['return_return'] = PHP_PAY_URL.'/'.self::name.'/syn.php';
        $key = self::key;
        $hach = 'md5';
        $arr['sign'] = self::inSign($arr,$key,$hach);
        $arr['cksign'] = strtoupper(md5('app_id='.$arr['app_id'].'&method='.$arr['method'].'&total_amount='.$arr['total_amount']));
        $url = self::url;
        $data = curl_post($url,$arr);
//        var_dump($data);exit;
        if($data['code'] == 0){

            $out = [
                'ret'=>[
                    'type'=>2,
                    'url'=>$data['url']
                ],
                'state'=>1
            ];
            echo json_encode($out);exit;
        }
        addlog(self::name.'/pt_other','pay error'.json_encode($data));
        echo json_encode(['state'=>-1,'msg'=>'mayi-pay error code 33']);exit;
    }
    //三方平台请求回调
    public static function callback($type){
        //同步回调
        if($type == 'syn'){
            echo 'SUCCESS';exit;
        }

        //异步回调
        $name = self::name;//日志目录---------------------------------------
        /*签名验证 start -----------------------------------------------------*/
        $hach = 'md5';
        $_POST['app_id'] = self::appid;
        $sign = self::outSign($_POST,self::otherkey,$hach);
        //签名验证失败

        if($sign != $_POST['sign']){
            addlog($name.'/other_pt','sign is error!--mysign:'.$sign.'------other-sign:'.json_encode($_POST));
            exit;
        }
        /*签名验证 end ------------------------------------------------------------*/
        //三方要求输出 "success"
        echo 'SUCCESS';
//第三方回调平台写入日志
        addlog($name.'/other_pt',json_encode($_POST));
//接收第三方支付回调订单号
        $order_id = $_POST['trade_no'];//-------------------------------------------
//平台回调游戏方
        Order::callback($order_id,$name);
    }

    public static function inSign($arr,$key,$hach){
        $str ='app_id='.$arr['app_id'];
        $str.='&buyer_id='.$arr['buyer_id'];
        $str.='&method='.$arr['method'];
        $str.='&return_notify='.$arr['return_notify'];
        $str.='&return_return='.$arr['return_return'];
        $str.='&total_amount='.$arr['total_amount'];
        $str.='&trade_no='.$arr['trade_no'];
        $str.='&app_secrect='.$key;
        $sign = ($hach == 'sha1') ? strtoupper(sha1($str)) : strtoupper(md5($str));
        return substr($sign,1);
    }
//通知签名
    public static function outSign($arr,$key,$hach){
        $str ='app_id='.$arr['app_id'];
        $str.='&method='.$arr['method'];
        $str.='&pay_time='.$arr['pay_time'];
        $str.='&trade_amount='.$arr['trade_amount'];
        $str.='&trade_no='.$arr['trade_no'];
        $str.='&app_secrect='.$key;
        $sign = ($hach == 'sha1') ? strtoupper(sha1($str)) : strtoupper(md5($str));
        return substr($sign,1);
    }
}
