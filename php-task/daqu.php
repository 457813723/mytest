<?php
require './service/MongoService.php';
require './service/MysqlService.php';
require './service/RedisService.php';
require './lib.php';
ini_set('date.timezone','Asia/Shanghai');
//America/Adak
echo date('H',time());exit;
ini_set('date.timezone','America/Adak');
echo date('Y-m-d H:i:s',time());exit;
$mysql = new MysqlService(['dbname'=>'db_detail']);
$count_day = 20190528;
$sql = "select COUNT_FEE,RECEIPTOR from pt_count_gather where COUNT_DAY=$count_day";
$res = $mysql->doSql($sql);
$redis = new Redis();
$redis->connect(config('redis_host'),config('redis_port'),2);
if(config('redis_pass') != ''){
    $redis->auth(config('redis_pass'));
}
$redis->select(3);
$agent = [];
foreach($res as $k=>$v){
    getUp($v['RECEIPTOR'],$mysql,$v['COUNT_FEE'],$agent,$redis);
}

foreach($agent as $k=>$v){
    //查询下级 代理类型，分成比例
    $sql = "select a.AGENT_TYPE,b.RATIO from pt_agent as a left join pt_agent_extend as b on a.AGENT_ID=b.AGENT_ID  where a.AGENT_ID=$k";
    $r = $mysql->doSql($sql);
    //如果自己是大区，也会增加一条自己对自己的贡献
    if($r[0]['AGENT_TYPE'] == 2){
        $sql = "select sum(COUNT_FEE) as ach_to_mine from pt_count_gather group by RECEIPTOR,COUNT_DAY having RECEIPTOR =$k and COUNT_DAY=$count_day";
        $myach = $mysql->doSql($sql);
        if(!empty($myach)){
            $ach_to_main = $myach[0]['ach_to_mine'];
            $sub_ratio = 0;//自己对自己的贡献，下级分成比例设置为0
            $agent_ratio = $r[0]['RATIO'];
            $time = date('Y-m-d H:i:s',time());
            $sql = "insert into pt_total_ach values(null,$count_day,$k,2,$k,0,$ach_to_main,'$time',$sub_ratio,0,$agent_ratio,0)";
            $mysql->doSql($sql);
        }
    }
    //去redis中获取上级
    $up_id = $redis->get($k);
    //查询上级
    $sql = "select a.AGENT_TYPE,a.AGENT_ID,b.RATIO from pt_agent as a left join pt_agent_extend as b on a.AGENT_ID=b.AGENT_ID  where a.AGENT_ID=$up_id";
    $up = $mysql->doSql($sql);
    if(empty($up)){//金字塔顶端的人,只需要记录自己给自己贡献的，如果有的话
    continue;
    }
    if($up[0]['AGENT_TYPE'] == 2){//如果上级是大区
        $income_ach = $v;//下级业绩
        $ach = $income_ach;//大区支线业绩
        $time = date('Y-m-d H:i:s',time());
        $sub_ratio = is_null($r[0]['RATIO'])?0:$r[0]['RATIO'];//下级分成比例
        $user_type = $r[0]['AGENT_TYPE'];
        $agent_id = $up[0]['AGENT_ID'];
        $agent_ratio = $up[0]['RATIO'];//上级分成比例
        $sql = "insert into pt_total_ach values(null,$count_day,$k,$user_type,$agent_id,$income_ach,$ach,'$time',$sub_ratio,0,$agent_ratio,0)";
        $mysql->doSql($sql);
    }
}

//更新pt_agent中的大区收益整线收益
$sql = "select sum(AGENT_INCOME) as dq_income,AGENT_ID from pt_total_ach group by R_DAY ,AGENT_ID having R_DAY=$count_day";
$dq_income_data = $mysql->doSql($sql);
$time = date('Y-m-d H:i:s',time());
foreach($dq_income_data as $k=>$v){
    $sql = "update pt_agent set AGENT_DAQU_INCOME = AGENT_DAQU_INCOME+".$v['dq_income']." where AGENT_ID=".$v['AGENT_ID'];
    try {
        $res = $mysql->doSql($sql);
    } catch (Exception $e) {
        continue;
    }
    //记录收益发放记录表pt_deposit_record（DEP_TYPE=1:代理后台将收益提现到游戏币； DEP_TYPE=2:日结算收益更新记录）
    $sql = "insert into pt_deposit_record values(null,".$v['AGENT_ID'].",2,1,".$v['dq_income'].",3,'".$time."',0)";
    $mysql->doSql($sql);
}

//pre($dq_income_data);
pre($agent);exit;


function getUp($rid,$mysql,$count_fee,&$agent,$redis){
    //查询上级
    $sql = "select UP_AGENT_ID from pt_agent where AGENT_ID=".$rid;
//    echo $sql;exit;
    $rs = $mysql->doSql($sql);
    if(isset($agent[$rid])){
        $agent[$rid] += (int)$count_fee;
    }else{
        $agent[$rid] = (int)$count_fee;
    }
    //保存层级关系到redis  3分片 下级=>上级
    $redis->set($rid,$rs[0]['UP_AGENT_ID']);
    if($rs[0]['UP_AGENT_ID'] == 0){
        return $agent;
    }else{
        getUp($rs[0]['UP_AGENT_ID'],$mysql,$count_fee,$agent,$redis);
    }

}






