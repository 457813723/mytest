<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/11
 * Time: 17:59
 */


//读取post参数并验证参数是否存在
function get_request($param,$is_must,$d=''){

    if(isset($_POST[$param])){
        return $_POST[$param];
    }
    if($is_must == 1){
        echo json_encode(['msg'=>'param is error','state'=>-1]);exit;
    }else {
        return $d;
    }
}
//配置文件
function config($item){
    $config = require('config.php');
    return isset($config[$item])?$config[$item]:false;
}
function getmenue(){
    $menue = require('menue.php');
    return $menue;
}

//获取订单号
function orderno(){
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn =
        $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
            'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
            '%02d', rand(0, 99));

    return $orderSn;
}

/*
 * curl post 请求
 * */
function curl_post($url,$data,$header=[],$isjson=false){
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

/*
 * $type:  other_pt:三方回调平台   pt_game：平台回调游戏
 * */
function addlog($filename,$msg){

    $file = config('log_path').$filename.'.log';
    if(!is_dir(dirname($file))){
        mkdir(dirname($file),0777,true);
    }
    $time = date('Y-m-d H:i:s',time());
    $content = $time.'---'.$msg."\n";
    file_put_contents($file,$content,FILE_APPEND);//追加写入

}

define('SECRETKEY', '12f862d21d3ceafba1b88e5f22960d55');

/**
 * 加密方法
 * @param string $str
 * @return string
 */
function encrypt($str) {
    //AES, 128 ECB模式加密数据
    $str = addPKCS7Padding($str);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
    $encrypt_str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, SECRETKEY, $str, MCRYPT_MODE_CBC, '0000000000000000');
    return base64_encode($encrypt_str);
}

/**
 * 解密方法
 * @param string $str
 * @return string
 */
function decrypt($str) {
    //AES, 128 CBC模式加密数据
    $str = base64_decode($str);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
    $encrypt_str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, SECRETKEY, $str, MCRYPT_MODE_CBC, '0000000000000000');
    $encrypt_str = stripPKSC7Padding($encrypt_str);
    return $encrypt_str;
}

/**
 * 填充算法
 * @param string $source
 * @return string
 */
function addPKCS7Padding($source) {
    $source = trim($source);
    $block = mcrypt_get_block_size('rijndael-128', 'cbc');
    $pad = $block - (strlen($source) % $block);
    if ($pad <= $block) {
        $char = chr($pad);
        $source .= str_repeat($char, $pad);
    }
    return $source;
}

/**
 * 移去填充算法
 * @param string $source
 * @return string
 */
function stripPKSC7Padding($source) {
    $char = substr($source, -1);
    $num = ord($char);
    $source = substr($source, 0, -$num);
    return $source;
}


 function strtoascii($str)
{
    $str = mb_convert_encoding($str, 'GB2312');
    $change_after = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $temp_str = dechex(ord($str[$i]));
        $change_after .= $temp_str[1] . $temp_str[0];
    }
    return strtoupper($change_after);
}

/**
 * 递归方式的对变量中的特殊字符去除转义
 *
 * @access  public
 * @param   mix     $value
 *
 * @return  mix
 */
function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}
/**
 * 递归方式的对变量中的特殊字符去除转义
 *
 * @access  public
 * @param   mix     $value
 *
 * @return  mix
 */
function stripslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    }
}

/**
 * 过滤用户输入的基本数据，防止script攻击
 *
 * @access      public
 * @return      string
 */
function compile_str($str)
{
    $arr = array('<' => '＜', '>' => '＞','"'=>'”',"'"=>'’');

    return strtr($str, $arr);
}

/*
 * 获取客户端IP
 */
function getRealIpAddr(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){//check ip from share internet
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){//to check ip is pass from proxy
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
