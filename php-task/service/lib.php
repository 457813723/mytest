<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/30
 * Time: 17:35
 */
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