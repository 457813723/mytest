<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 20:57
 */

class agentincome
{
    public  function index()
    {
        $rid = 11;
        $page = get_request('page',0,1);
        $page_size = get_request('size',0,10);
        $start = isset($_POST['bt'])?str_replace('-','',$_POST['bt']):date('Ymd');
        $end = isset($_POST['et'])?str_replace('-','',$_POST['et']):date('Ymd',strtotime(date('Y-m-d'))+24*3600);
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        $offset = $page_size*$page;
        $sql = "select 
                    sum(a.COUNT_FEE) as countFee,a.CONTRIB_ID , b.agent_type,b.rid 
                from 
                    pt_count_gather as a 
                left join 
                    pt_agent as b on a.CONTRIB_ID = b.rid  
                where 
                    a.RECEIPTOR=$rid and a.COUNT_DAY between $start and $end 
                GROUP BY 
                    a.CONTRIB_ID 
                limit $offset , $page_size;";

        $res = $pdo->doSql($sql);

        $out = [
            'ret'=>[
                'rows'=>[],
                'total'=>0
            ],
            'code'=>1
        ];
        if(empty($res)){
            echo json_encode($out);exit;
        }
        $data = [];
        $total = 0;
        foreach($res as $k=>$v){
            if($v['agent_type'] == 1){
                $agent_type = '玩家';
            }else if($v['agent_type'] == 2){
                $agent_type = '代理';
            }else{
                $agent_type = $v['agent_type'];
            }
            $data[] = [
                'playerId'=>$v['CONTRIB_ID'],
                'nickName'=>'玩家'.rand(1,9),
                'playerType'=>$agent_type,
                'countFee'=> sprintf("%.2f",  $v['countFee'])
            ];
            $res[$k]['rolename'] = '玩家'.$v['rid'];
        }
        $out['ret']['total'] = 50;
        $out['ret']['rows'] = $data;
        echo  json_encode($out);exit;
    }
}