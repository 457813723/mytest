<?php
/**
 * 天天-和通对接
 * @time   2019-03-28
 * @author song.wang
 * @Class  hetong
 */
class hetong
{
    const app_id = "201907251516446801";    //商户编号
    const app_name = "hetong";
    const app_key = "3f12ea76fc4e9b8e2bdee6528490db38"; //交易Key
    //游戏请求支付
    public static function pay($m)
    {

        //访问平台生成的二维码支付页面
        if(substr($m,0,6) == 'jdview'){
            $qrcode_url = base64_decode(str_replace('jdview','',$m));
            $str = file_get_contents('../view/jd.html');
            $str = str_replace('${reqUrl}',$qrcode_url,$str);
            echo $str;exit;
        }
        //扫码类型
        $map = array(
            "alipay"=>"ALIPAY_QRCODE_PAY",       //支付宝 1
            "weixin"=>"WECHAT_QRCODE_PAY",       //微信   2
            'jd'=>"JD_QRCODE_PAY",                //京东扫码 3
        );
        if(empty($map[$m]))
        {
            exit(json_encode(['state' => -1, 'msg' => '支付类型错误']));
        }
        //1、本平台数据库生成订单
        $res = Order::createOrder($_GET);

        if(isset($res['error']))
        {
            echo  json_encode(array(
                "state"=>-1,
                "msg"=>$res['error'],
            ));exit;
        }
        //获取本平台订单ID
        $order_id = $res['order_id'];
        //获取交易价格

        $price = $res['price'] * 100;
        //2、拼接第三方交互参数
        $tjurl = "https://go.bjhta.cn/pay/pay";   //目标地址
        //计算MD5签名
        $current_data = date("Y-m-d H:i:s");
        $sign = md5("notify_url=".PHP_PAY_URL . '/hetong/asy.php'."&"."out_trade_no="
            .$order_id."&"."partner=".self::app_id."&"."payment_type=".$map[$m]."&"."timestamp=".
            $current_data."&"."total_fee=".$price.self::app_key);
        $content = array(
            'partner'=>self::app_id,          //商家编号
            'out_trade_no'=>$order_id,        //订单编号
            'total_fee'=>$price,              //价格
            'notify_url'=>PHP_PAY_URL . '/hetong/asy.php',  //异步回调地址
            'payment_type'=>$map[$m],               //支付类型
            'timestamp'=>$current_data,       //发起交易时间
            'sign' => $sign,            //md5签名
        );
        addlog(self::app_name . '/pt_other', 'pay error--平台传递第三方数据' . json_encode($content));
        //发送请求
        $data = self::curl_post($tjurl, $content,array(),false,true);
        var_dump($data);exit;
        //判断同步响应类型
        if($data['code'] == '0')
        {
            //若成功，得到支付二维码路径
            $qrcode_url = $data['qrcode_url'];
            addlog(self::app_name . '/pt_other', 'pay success--' . json_encode($data));
            //这里把二维码页面组装生成一个Html页面提交给游戏
            //利用二维码路径和页面模板，生成一个新的页面提交给游戏端
            $out = array(
                'ret'=>array(
                    'type'=>2,
                    'url'=>$qrcode_url
                ),
                'state'=>1, //成功
            );
            //京东扫码支付需要平台根据qrcodeurl生成对应二维码的支付页面
//            if($m == 'jd'){
                $out['ret']['url'] = PHP_CLIENT_URL.'/'.self::app_name.'/jdview'.base64_encode($qrcode_url).'.php';
//            }
            exit(json_encode($out));
        }
        else
        {
            addlog(self::app_name . '/pt_other', 'pay error--' . json_encode($data));
            exit(json_encode(['state' => -1, 'msg' => 'pay error code 33']));
        }
    }
    //三方平台请求回调
    public static function callback($type)
    {
        //同步回调
        if ($type == 'syn')
        {
            exit('success');
        }
        //异步回调
        //验证md5签名
        addlog(self::app_name.'/other_pt', '接收到反馈的post参数' . json_encode($_POST));
        $order_id = $_POST['out_trade_no'];
        $sign = md5("code=".$_POST['code']."&"."out_trade_no=".$_POST['out_trade_no']."&"."partner=".$_POST['partner']."&"."service_charge=".$_POST['service_charge']."&"."state=".$_POST['state']."&"."timestamp=".$_POST['timestamp']."&"."total_fee=".$_POST['total_fee']."&"."trade_no=".$_POST['trade_no'].self::app_key);
        if ($sign != strtolower($_POST['sign']))
        {
            addlog(self::app_name.'/other_pt', 'sign is error!--' . json_encode($_POST));
            exit;
        }
        /*签名验证 end ------------------------------------------------------------*/
        if($_POST['state'] !=  "S"){
            addlog(self::app_name.'/other_pt','pay status false!--'.json_encode($_POST));
            Order::update($order_id,2);//将平台订单状态改为支付失败
            echo 'success';
            exit;
        }
        echo 'success';
        addlog(self::app_name.'/other_pt', json_encode($_POST));
        //平台回调游戏方
        $res = Order::callback($order_id, self::app_name);
        if (isset($res['status']) && $res['status'] == 'success')
        {
            addlog(self::app_name.'/other_pt', '平台回调游戏成功');
            //回调成功修改订单状态
            Order::update($order_id);
        }
        else
        {
            //回调失败在db_system.pt_transactional_retry中添加纪录
            $rs = Order::callbackfalsedo($order_id);
            if (isset($rs['error']))
            {
                addlog(self::app_name.'/other_pt', $rs['error']);
            }
        }
    }

    private static function curl_post($url,$data,$header=[],$isjson=false,$is_https=false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//重定向
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        //header
        if(!empty($header)){
            curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        }
        if($is_https)
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }
        if($isjson){
            $data = json_encode($data);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);//json字符串
        $res = curl_exec($curl);
        //var_dump( curl_getinfo($curl));exit;
        curl_close($curl);
        $array = json_decode($res,true);
        if(!is_array($array)){
            return $res;
        }else{
            return $array;
        }
    }
}