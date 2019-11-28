<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/11
 * Time: 17:59
 */



//配置文件
function config($item){
    $config = require('config.php');
    return isset($config[$item])?$config[$item]:false;
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
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
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
//    var_dump($res);exit;
//    curl_close($curl);
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
function addlog($type,$msg){

    $file = config('log_path').$type.'.log';
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
 *  加密
 */
//$string =encrypt('{"key":"value","key1":"value1"}');
//print_r($string);
//echo '<hr>';
///**
// *  解密
// */
//$string=decrypt($string);
//print_r($string);exit;

function dump()
{
    header("Content-type : text/html; charset=UTF-8");
    $funlist = func_get_args();
    $exitstring = 'exit'; //如果包含EXIT执行完后则停止
    foreach($funlist as $key => $val)
    {
        if($val == $exitstring)
        {
            continue;
        }
        do_dump($val, $key + 1);
        echo "<hr/>";
    }
    if(array_search($exitstring, $funlist) !== false)
    {
        exit();
    }
}

function do_dump(&$var, $var_name = null, $indent = null, $reference = null)
{
    $codetype = 'UTF-8';
    $do_dump_indent = "<span style='color:#666666;'>|</span> &nbsp;&nbsp; ";
    $reference = $reference . $var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme';
    $keyname = 'referenced_object_name';

    // So this is always visible and always left justified and readable
    echo "<div style='text-align:left; background-color:white; font: 100% monospace; color:black;'>";

    if(is_array($var) && isset($var[$keyvar]))
    {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        echo "$indent$var_name <span style='color:#666666'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
    }
    else
    {
        $var = array(
            $keyvar => $var,
            $keyname => $reference
        );
        $avar = &$var[$keyvar];

        $type = ucfirst(gettype($avar));
        if($type == "String")
        {
            $type_color = "<span style='color:green'>";
        }
        elseif($type == "Integer")
        {
            $type_color = "<span style='color:red'>";
        }
        elseif($type == "Double")
        {
            $type_color = "<span style='color:#0099c5'>";
            $type = "Float";
        }
        elseif($type == "Boolean")
        {
            $type_color = "<span style='color:#92008d'>";
        }
        elseif($type == "NULL")
        {
            $type_color = "<span style='color:black'>";
        }

        if(is_array($avar))
        {
            $count = count($avar);
            echo "$indent" . ($var_name ? "$var_name => " : "") . "<span style='color:#666666'>$type ($count)</span><br>$indent(<br>";
            $keys = array_keys($avar);
            foreach($keys as $name)
            {
                $value = &$avar[$name];
                do_dump($value, "['$name']", $indent . $do_dump_indent, $reference);
            }
            echo "$indent)<br>";
        }
        elseif(is_object($avar))
        {
            echo "$indent$var_name <span style='color:#666666'>$type</span><br>$indent(<br>";
            foreach($avar as $name => $value)
            {
                do_dump($value, "$name", $indent . $do_dump_indent, $reference);
            }
            echo "$indent)<br>";
        }
        elseif(is_int($avar))
        {
            echo "$indent$var_name = <span style='color:#666666'>$type(" . mb_strlen($avar, $codetype) . ")</span> $type_color" . htmlspecialchars($avar) . "</span><br>";
        }
        elseif(is_string($avar))
        {
            echo "$indent$var_name = <span style='color:#666666'>$type(" . mb_strlen($avar, $codetype) . ")</span> $type_color\"" . htmlspecialchars($avar) . "\"</span><br>";
        }
        elseif(is_float($avar))
        {
            echo "$indent$var_name = <span style='color:#666666'>$type(" . mb_strlen($avar, $codetype) . ")</span> $type_color" . htmlspecialchars($avar) . "</span><br>";
        }
        elseif(is_bool($avar))
        {
            echo "$indent$var_name = <span style='color:#666666'>$type(" . mb_strlen($avar, $codetype) . ")</span> $type_color" . ($avar == 1 ? "TRUE" : "FALSE") . "</span><br>";
        }
        elseif(is_null($avar))
        {
            echo "$indent$var_name = <span style='color:#666666'>$type(" . mb_strlen($avar, $codetype) . ")</span> {$type_color}NULL</span><br>";
        }
        else
        {
            echo "$indent$var_name = <span style='color:#666666'>$type(" . mb_strlen($avar, $codetype) . ")</span> " . htmlspecialchars($avar) . "<br>";
        }

        $var = $var[$keyvar];
    }
    echo "</div>";
}