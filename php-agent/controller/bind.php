<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 20:57
 */

class bind
{
    public  function bindagent()
    {
        //权限验证

        $rid = get_request('userId',1);
        $invite_code= get_request('invite_code',1);
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        $sql = "select * from pt_agent where rid = $rid";
        $res1 = $pdo->doSql($sql);
        if(empty($res1)){
            $out = [
                'code'=>0,
                'msg'=>'Players is not exist',
                'content'=>[]
            ];
            echo  json_encode($out);exit;
        }
        //判断该用户是否满足两个条件：1.没有绑定过；2.是普通用户
        if($res1[0]['agent_type'] !=1 || $res1[0]['upagent_id'] !=0){
            $out = [
                'code'=>0,
                'msg'=>'user has binded',
                'content'=>[]
            ];
            echo  json_encode($out);exit;
        }

        $sql = "select * from pt_agent where rid = $invite_code";
        $res2 = $pdo->doSql($sql);
        if(empty($res2)){
            $out = [
                'code'=>0,
                'msg'=>'invite_code do not exist',
                'content'=>[]
            ];
            echo  json_encode($out);exit;
        }
        //判断 被绑定人是否是代理
        if($res2[0]['agent_type'] === 1){
            $out = [
                'code'=>0,
                'msg'=>'invite_code is not agent',
                'content'=>[]
            ];
            echo  json_encode($out);exit;
        }
        //绑定
        $sql = "update pt_agent set upagent_id=$invite_code where rid=$rid";
        $res = $pdo->doSql($sql);
        if(!$res){
            $out = [
                'code'=>0,
                'msg'=>'bind false',
                'content'=>[]
            ];
            echo  json_encode($out);exit;
        }

        $out = [
            'code'=>1,
            'msg'=>'bind success',
            'content'=>[]
        ];
        echo  json_encode($out);exit;
    }
}