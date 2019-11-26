<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 16:15
 */

class guoxin{
    const appid = '100064';
    const md5key = '8275cf0f0870c4076fc35d6b96208d16';
    const asekey = 'd9adb1056e73ffc3';
    const name ='guoxin';
    //游戏请求支付
    public static function pay($m){

        $map = ['alipay'=>'300020','wx'=>'300010'];  //--------------------
        $method = $map[$m];
        $res = Order::createOrder($_GET);//生成订单
        if(isset($res['error'])){
            echo json_encode(['state'=>-1,'msg'=>$res['error']]);exit;
        }
        $order_id = $res['order_id'];
        $price = $res['price'];
        /**准备入款订单参数*/
        $tjurl = "http://interface.tangjinhe.cn/interface/pay/order";   //提交地址
        $appid = self::appid;//appid
        $content = [
            'amount'=>$price,
            'orderno'=>$order_id,
            'subject'=>'msg',
            'createip'=>'139.9.62.111',//ip
            'notifyurl'=>PHP_PAY_URL.'/guoxin/asy.php',  //异步回调地址
            'syurl'=>PHP_PAY_URL.'/guoxin/syn.php'  //同步回调地址,
        ];
        $aeskey = self::asekey;
        $content = stripslashes(json_encode($content));
        $aes = new Aes($aeskey);
        $content = $aes->encrypt($content);
        $md5key = self::md5key;
        $sign = 'appid='.$appid.'&content='.$content.'&paytype='.$method.'&key='.$md5key;
        $sign = md5($sign);
        $data = [
            'appid'=>$appid,
            'paytype'=>$method,
            'content'=>$content,
            'sign'=>$sign
        ];
        $url = $tjurl;
        $form_data = $data;
        $data = curl_post($url,$form_data);

        if($data['code'] == '10000'){
            $content = $aes->decrypt($data['content']);
            $content = json_decode($content,true);
            $out = [
                'ret'=>[
                    'type'=>2,
                    'url'=>$content['url']
                ],
                'state'=>1
            ];
            echo json_encode($out);exit;
        }
        addlog(self::name.'/pt_other','pay error--'.json_encode($data));
        echo json_encode(['state'=>-1,'msg'=>'请求第三方失败']);exit;

    }

    //三方平台请求回调
    public static function callback($type){
        //同步回调
        if($type == 'syn'){
            echo 'success';exit;
        }

        //异步回调
        $name = 'guoxin';//日志目录---------------------------------------
        $content = $_POST['content'];
        $aeskey = self::asekey;
        $aes = new Aes($aeskey);
        $contenttxt = $aes->decrypt($content);//明文
        $order_id = json_decode($contenttxt,true)['orderno'];

        /*签名验证 start -----------------------------------------------------*/
        $md5key = self::md5key;
        $sign = 'code='.$_POST['code'].'&content='.stripslashes($content).'&msg='.$_POST['msg'].'&key='.$md5key;
        $sign = md5($sign);
//        //签名验证失败
        if($sign != $_POST['sign']){
            addlog($name.'/other_pt','sign is error!--'.json_encode($_POST));
            exit;
        }
        /*签名验证 end ------------------------------------------------------------*/
        //交易失败
        $r = json_decode($contenttxt,true);
        if($r['status'] != 2){
            addlog($name.'/other_pt','pay status false!--'.json_encode($_POST));
            Order::update($order_id,2);//将平台订单状态改为支付失败
            echo 'success';
            exit;
        }
        echo 'success';

//第三方回调平台写入日志

        addlog($name.'/other_pt',json_encode($_POST));
//接收第三方支付回调订单号（平台的订单id）

//平台回调游戏方
        $name = 'guoxin';
        Order::callback($order_id,$name);

    }
}