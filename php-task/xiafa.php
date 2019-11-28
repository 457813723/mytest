<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 18:32
 */
require './service/MongoService.php';
require './service/MysqlService.php';
require './lib.php';


$a = choiceAliNumber();
var_dump($a);
 function choiceAliNumber(){
     $mysql = new MysqlService(['dbname'=>'game_1']);
    //查询出所有的支付宝通道
    $sql = "select 
              a.ENG_NAME,a.CHANNEL_WEIGHT,a.WITH_TYPE,b.APP_NAME,b.APP_ID,b.APP_PRIVATE_KEY,b.APP_PUBLICK_KEY
            from db_detail.pt_with_channel as a 
            join db_detail.pt_alipay_msg as b 
            on  a.ENG_NAME=b.APP_NAME
            where WITH_TYPE=1 and a.CHANNEL_WEIGHT > 0";
    $all_channel = $mysql->doSql($sql);
//    var_dump($all_channel);exit;
    //根据权重选择一个支付宝
    $weight = 0;
    $data = array();
    foreach ($all_channel as $one) {
        $oneWeight = (int)$one['CHANNEL_WEIGHT'] ? $one['CHANNEL_WEIGHT'] : 1;
        $weight += $oneWeight;
        for ($i = 0; $i < $oneWeight; $i ++) {
            $data[] = $one;
        }
    }
    return $data[rand(0, $weight-1)];


}