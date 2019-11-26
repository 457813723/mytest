<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 20:57
 */

class myinfo extends common
{
    public  function index()
    {
        $rid = self::$rid;
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        $sql = "select * from pt_agent where rid = $rid";
        $res1 = $pdo->doSql($sql);

        if(empty($res1)){
            $out = [
                'code'=>2,
                'msg'=>'该玩家暂时不存在',
                'content'=>[]
            ];
            echo  json_encode($out);exit;
        }
        //推广收益
        $genToday = $res1[0]['history_income'];
        //今日新增扫码
        $start = date('Y-m-d');
//        echo $start;exit;
        $end = date('Y-m-d H:i:s',strtotime($start)+24*3600);
        $sql = "select count(*) as num from pt_agent where upagent_id=$rid and bind_time between  '$start' and '$end'";
        $res = $pdo->doSql($sql);
        $newScanToday = $res[0]['num'];
        //我的茶楼
        $sql = "select count(*) as num from pt_agent where upagent_id=$rid ";
        $res = $pdo->doSql($sql);
        $myTeaHouse = $res[0]['num'];
        //推广收益

        $out = [
            'code'=>1,
            'msg'=>'success',
            'content'=>[
                'playerId'=>$res1[0]['rid'],
                'userType'=>$res1[0]['agent_type'],
                'nickName'=>'Leslie',
                //头像
                'headImg'=>'http://thirdwx.qlogo.cn/mmopen/vi_32/ibzNBmlMYRBkKTR4frrj45jUrUAUphELlOrnZgtrXvc99hx3a7iadeedmBibFaR6XJv8OxMwKlXdkV0oHOQLHCFfA/132 ',
                'up_id'=>$res1[0]['upagent_id'],
                'myTeaHouse'=>$myTeaHouse,//下级人数
                'newScanToday'=>$newScanToday,//今日新增扫码
                'genToday'=>$genToday,//推广收益
                'usable_income'=>$res1[0]['usable_income'],
                'history_agent'=>$res1[0]['history_income'],
                'used_income'=>$res1[0]['used_income'],
            ]
        ];
        echo  json_encode($out);exit;
    }
}