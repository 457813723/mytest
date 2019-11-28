<?php
class qrcode extends common {

    public function create_qrcode(){

        $rid = self::$rid;
        $pdo = new mysql(['dbname'=>DB_AGENT]);
        $sql = "select * from db_system.pt_dict_config where TAG='php_create_user'";
        $res = $pdo->doSql($sql);
        if(empty($res)){
            $out = [
                'code'=>0,
                'msg'=>'php_create_user config error',
                'ret'=>[]
            ];
            echo  json_encode($out);exit;
        }
        $url = $res[0]['VAL'].'?rid='.$rid;
        echo json_encode(['url'=>$url,'code'=>1]);
    }
}