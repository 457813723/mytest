<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 16:15
 */
class ssfustd{
    const appid = '1000093';
    const accessKeyId = '4a9c45e0-15ff-43d0-bc7c-f6a708ea4505';
    const accessKeySecret = 'd3390c53-de45-4138-b90b-f0b05d9ab74b';
    const calbackSignKey = '49f0250a-030b-4a05-862a-3a2928887498';
    const name ='ssfustd';
    //游戏请求支付
    public static function pay($m){
        $map = ['alipay'=>'300020','ysf'=>'300030'];  //--------------------
        $method = $map[$m];
        $res = Order::createOrder($_GET);//生成订单
        if(isset($res['error'])){
            echo json_encode(['state'=>-1,'msg'=>$res['error']]);exit;
        }
        $order_id = $res['order_id'];
        $price = $res['price'];
        /**准备入款订单参数*/
        $url = "http://bitpay-api.money123.vip/api/createOrder";   //提交地址
// 访问密钥ID,开户邮件中获取
        $accessKeyId = self::accessKeyId;
// 访问签名密钥，开户邮件中获取
        $accessKeySecret = self::accessKeySecret;
// 回调验签密钥，注意回调的签名一定要这个验证,开户邮件中获取
        $callBackSignKey = self::calbackSignKey;
// API请求域，请使用正确的，开户邮件中获取
        $apiHost = "http://bitpay-api.money123.vip";
// 收银台地址，开户邮件中获取,拼接格式 $cashDeskHost/#/loading?businessId=$businessId
        $cashDeskHost = "http://cashdesk.money123.vip";
// 币种编号，商户后台中获取
        $coinId = "USDT";
// 币种地址，商户后台中获取

        $address = "0x8e14950df593e691cce4b73735960e544e0ad156";
// 随机值
        $nonce = self::random();
// 时间戳
        $timestamp = time();
//支付类型，请参考文档
        $payType = "alipay";
// 请求内容，json格式，具体字段意义参考API文档，注意coinId和address一定要换成正确的
        $order = array('accessOrderId' => $order_id,'address' => $address,'amount' => $price, 'coinId'=>$coinId,'payType'=>$payType , 'productName'=>'话费充值');
        /*$body = '{"accessOrderId":"TEST155869305370783","address":"'.$address.'","amount":1.00,"coinId":"'.$coinId.'","payType":"'.$payType.'","productName":"话费充值"}';*/
//请求体以json格式
        $body = json_encode($order);
// 请求体md5值
        $bodyMd5 = strtolower(md5($body));
        $sign = strtolower(md5($accessKeyId.$nonce.$timestamp.$bodyMd5.$accessKeySecret));
        $params = "accessKeyId=".$accessKeyId."&timestamp=".$timestamp."&nonce=".$nonce."&bodyMd5=".$bodyMd5."&sign=".$sign;
// echo $params;
        $resp = self::recharge($apiHost, $params, $body);
        if($resp->code == 200){
            //返回200表示下单成功
            $businessId = $resp->data->businessId;
            $locationUrl = $cashDeskHost."/#/loading?businessId=".$businessId;
            $out = [
                'ret'=>[
                    'type'=>2,
                    'url'=>$locationUrl
                ],
                'state'=>1
            ];
            echo json_encode($out);exit;
        }
        addlog(self::name.'/pt_other','pay error--'.json_encode($resp));
        echo json_encode(['state'=>-1,'msg'=>'ssfustd-pay error code 33']);exit;
    }
    //三方平台请求回调
    public static function callback($type){
        //同步回调
        if($type == 'syn'){
            echo 'success';exit;
        }
        //异步回调
        $name = 'ssfustd';//日志目录---------------------------------------
        /*签名验证 start -----------------------------------------------------*/
        $timestamp = $_GET['timestamp'];
        $sign = $_GET['sign'];
        $callbackSignKey = self::calbackSignKey;
        $nonce = $_GET['nonce'];
        $body = file_get_contents('php://input');
//        //签名验证失败
        if(self::checkSign($body,$callbackSignKey,$nonce,$timestamp,$sign) != 0){
            addlog($name.'/other_pt','sign is error!--'.json_encode($body));
            exit;
        }
        $bodyArray = json_decode($body,true);
        // 获取充值传入的订单号
        $order_id = $bodyArray['businessId'];
        $pay_id =$bodyArray['tradeId'];
//第三方回调平台写入日志
        addlog($name.'/other_pt',json_encode($body));
//接收第三方支付回调订单号（平台的订单id）
//平台回调游戏方
        Order::callback($order_id,$name,$pay_id);
    }
    public static function recharge($apiHost,$params,$body){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiHost."/acceptComp/business/buy?".$params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            exit;
        } else {
            return json_decode($response);
        }
    }
    public static function random($length = 6, $type = 'string', $convert = 0)
    {
        $config = array(
            'number' => '1234567890',
            'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
            'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
        );
        if (!isset($config[$type]))
            $type = 'string';
        $string = $config[$type];
        $code = '';
        $strlen = strlen($string) - 1;
        for ($i = 0; $i < $length; $i++) {
            $code .= $string{mt_rand(0, $strlen)};
        }
        if (!empty($convert)) {
            $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
        }
        return $code;
    }
    public static function checkSign($body,$callbackSignKey,$nonce,$timestamp,$sign){
        return strcmp(strtolower(md5($body.$callbackSignKey.$nonce.$timestamp)),$sign);
    }
}
