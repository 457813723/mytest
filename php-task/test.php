<?php
require './service/MongoService.php';
require './service/MysqlService.php';
require './lib.php';
ini_set('date.timezone','Asia/Shanghai');
$mongo = new MongoService();
$mysql = new MysqlService(['dbname'=>'db_detail']);
$ymd = date('Y_m_d');
$h =date('H');
//$collection = $ymd.'.servicechargelog_'.$h;
$collection = '2019_05_26.servicechargelog_17';
$filter = [
    'time_stamp'=>['$mod'=>[5,0]],//对5取模
    'status'=>['$ne'=>1]
];
$options = [
    'projection' => ['_id'=>1,'rid'=>1,'service_charge'=>1,'time_stamp'=>1], //选需要的字段，id是默认的
    'limit'=>500,
    'skip'=>0

    //    'sort' => ['user_id'=>-1] //根据user_id字段排序 1是升序，-1是降序
];
//对rid求模，将通一个rid的人的记录 先累加再 存sql 可以减少sql次数
$res = $mongo->query($collection,$filter,$options);

$count_day = date('Ymd',time());
$time = date('Y-m-d H:i:s',time());
if(!empty($res)){
    foreach($res as $k=>$v){
        //获取五级rid，如果自己是代理 从自己开始算，如果自己是玩家就从上级开始算
        $level_map = get_level5($mysql,$v['rid']);//array(3) { [0]=> int(2001394) [1]=> int(2003790) [2]=> int(2000367) }
        $number = 1;
        foreach($level_map as $kk=>$vv){
            $count_fee = $v['service_charge']/100 * config($kk);//计算该级所获得的收益
            if($kk == 0){//贡献者rid
                $contrib_id = $v['rid'];
            }else{
                $contrib_id = $level_map[$kk-1];
            }
            //更新五级返利日结算pt_count_gather
            $sql = "insert into pt_count_gather values(null,$count_day,".$contrib_id.",$number,".$count_fee.",".$vv.",'".$time."','".$time."',0)";
            try{
                $r = $mysql->doSql($sql);
            }catch (Exception $e){
                //捕获唯一键报错
                if(strstr($e->getMessage(),'query_index')){
                    $sql = "update pt_count_gather set COUNT_FEE=COUNT_FEE+".$count_fee." where COUNT_DAY=$count_day and CONTRIB_ID=".$contrib_id." and NUMBER=$number and RECEIPTOR = ".$vv;
                    $r = $mysql->doSql($sql);
                }
            }
            //更新pt_agent 的累计五级收益
            $sql = "update pt_agent set AGENT_INCOME = AGENT_INCOME+$count_fee where AGENT_ID=$vv";
            $r = $mysql->doSql($sql);
            $number ++;
        }
    }
    //更改mongo服务记录结算状态
    $data = [];
    foreach($res as $k=>$v){
            $obj_id = new \MongoDB\BSON\ObjectId($v['_id']);
            $data[] = [
                ['_id' => $obj_id],
                ['$set' => ['status' => 1]],
                ['multi' => false, 'upsert' => false]
            ];
    }
    $r = $mongo->update($collection,$data);
}

function get_level5($mysql,$rid){
    //往上五级代理
    $level_map = [];
    //一级
    $sql = "select a.PLAYER_ID,a.USER_TYPE, a.UP_PLAYER_ID,b.UP_AGENT_ID  from pt_player_extend as a left join pt_agent as b on a.PLAYER_ID=b.AGENT_ID where a.PLAYER_ID=$rid";
    $res = $mysql->doSql($sql);
    if(empty($res)){//没查询到玩家
       return [];
    }
    if($res[0]['UP_PLAYER_ID'] == 0){//不在层级树
        return [];
    }
    //只要该玩家在层级树中，不是一个孤立的点，那么上面第一级始终是pt_player_extend中的up_player_id
    $level_map[] = $res[0]['UP_PLAYER_ID'];
    //二级
    $sql = "select UP_AGENT_ID from pt_agent where AGENT_ID=".$res[0]['UP_PLAYER_ID'];
    $res = $mysql->doSql($sql);
    if(empty($res)  || $res[0]['UP_AGENT_ID'] == 0){//没查询到上级
        return $level_map;
    }
    $level_map[] = $res[0]['UP_AGENT_ID'];

    //三级
    $sql = "select UP_AGENT_ID from pt_agent where AGENT_ID=".$res[0]['UP_AGENT_ID'];
    $res = $mysql->doSql($sql);
    if(empty($res) || $res[0]['UP_AGENT_ID'] == 0){//没查询到上级
        return $level_map;
    }
    $level_map[] = $res[0]['UP_AGENT_ID'];

    //四级
    $sql = "select UP_AGENT_ID from pt_agent where AGENT_ID=".$res[0]['UP_AGENT_ID'];
    $res = $mysql->doSql($sql);
    if(empty($res)  || $res[0]['UP_AGENT_ID'] == 0){//没查询到上级
        return $level_map;
    }
    $level_map[] = $res[0]['UP_AGENT_ID'];
    //五级
    $sql = "select UP_AGENT_ID from pt_agent where AGENT_ID=".$res[0]['UP_AGENT_ID'];
    $res = $mysql->doSql($sql);
    if(empty($res)  || $res[0]['UP_AGENT_ID'] == 0){//没查询到上级
        return $level_map;
    }
    $level_map[] = $res[0]['UP_AGENT_ID'];
    return $level_map;
}
