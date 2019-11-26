<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 20:57
 */

class daquincome
{
    public  function index()
    {
        $rid = 2000112;
        $page = isset($_GET['page'])?$_GET['page']:1;
        $page_size = isset($_GET['limit'])?$_GET['limit']:10;
        $pdo = new mysql(['dbname'=>'layui']);
        $offset = $page_size*($page-1);
        $sql = "select rid,agent_type,bind_time from player where upagent_id = $rid  limit $offset, $page_size";
        $res = $pdo->doSql($sql);
        foreach($res as $k=>$v){
            if($v['agent_type'] == 1){
                $res[$k]['agent_type'] = '玩家';
            }else if($v['agent_type'] == 2){
                $res[$k]['agent_type'] = '代理';
            }else if($v['agent_type'] == 3){
                $res[$k]['agent_type'] = '大区';
            }
            $res[$k]['rolename'] = '玩家'.$v['rid'];
        }

        $out = [
            'code'=>0,
            'count'=>count($res),
            'msg'=>'success',
            'data'=>$res

        ];
        echo  json_encode($out);exit;
    }
}