<?php
function config($item){
    $config = require('config.php');
    return isset($config[$item])?$config[$item]:false;

}
function pre($array){
    echo '<pre>';
    var_dump($array);
    echo '</pre>';

}

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

function addlog($filename,$msg,$is_append=true){

    $file = config('log_path').$filename.'.log';
    if(!is_dir(dirname($file))){
        mkdir(dirname($file),0777,true);
    }
    $time = date('Y-m-d H:i:s',time());
    $content = $time.'---'.$msg."\n";
    if($is_append){
        file_put_contents($file,$content,FILE_APPEND);//追加写入
    }else{
        file_put_contents($file,$content);
    }
}
function getClubShare($h){
    if($h <4){
        return  0;
    }else if($h <12){
        return 1;
    }else if($h <20){
        return 2;
    }else{
        return 3;
    }
}


function getShareByhour($h){
    if($h <2){
        return 0;
    }else if($h <4){
        return  1;
    }else if($h <8){
        return 2;
    }else if($h <12){
        return 3;
    }else if($h<16){
        return 4;
    }else if($h <20){
        return 5;
    }else if ($h <22){
        return 6;
    }else {
        return 7;
    }
}

function sendMsg($msg){
    $url = 'http://59.110.69.166:8080/sms.aspx';
    $data = [
        'userid'=>'621',
        'account'=>'htx',
        'password'=>'htx888888',
        'mobile'=>config('msg_receptor'),
        'content'=>$msg,
        'sendTime'=>'',
        'action'=>'send',
        'extno'=>''
    ];
    curl_post($url,$data);
}

function sendEmail($title,$msg){
    $url = config('email_api').'api/send_mail';
    $receptor = config('email_receptor');
    $url .='?receiveMailAccount='.$receptor.'&title='.$title.'&content='.$msg;
    file_get_contents($url);
////    echo $url;exit;
//    //初始化
//    $curl = curl_init();
//    //设置抓取的url
//    curl_setopt($curl, CURLOPT_URL, $url);
//    //设置头文件的信息作为数据流输出
//    curl_setopt($curl, CURLOPT_HEADER, 1);
//    //设置获取的信息以文件流的形式返回，而不是直接输出。
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//    //执行命令
//    $data = curl_exec($curl);
//    var_dump($data);
//    //关闭URL请求
//    curl_close($curl);
    //显示获得的数据
}