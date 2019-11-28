<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/10
 * Time: 17:50
 */

class MongoService{
    const Db = 'myteahouse';
    public $Manager = null;
    public function __construct(){
        $user = config('mongo_user');
        $pwd = config('mongo_pwd');
        $ip = config('mongo_host');
        $port = config('mongo_port');
//        $db = $info['db'];
        if(!empty($user) || !empty($pwd)){
            $this->Manager = new MongoDB\Driver\Manager("mongodb://$user:$pwd@$ip:$port");
        }else{
            $this->Manager = new MongoDB\Driver\Manager("mongodb://$ip:$port");
        }

    }

    /*
     * 查询
     * */
    public  function query($collection,$filter,$options){
//        $filter =  ['user_id'=>['$gt'=>0]]; //查询条件 user_id大于0
//        $filter = [];

        $query = new MongoDB\Driver\Query($filter, $options); //查询请求
        $list =$this->Manager->executeQuery($collection,$query)->toArray(); // 执行查询 location数据库下的box集合
        $data = [];
        foreach ($list as $k=>$v) {
            foreach($v as $kk=>$vv){
                if($kk == '_id'){
                    $data[$k][$kk] = $vv->__toString();
                }else{
                    $data[$k][$kk]= $vv;
                }
            }

        }
        return $data;
    }
    public function command($db,$cmd){
        $command = new \MongoDB\Driver\Command($cmd);
        $res = $this->Manager->executeCommand($db,$command)->toArray();
        return $res;
    }
    public function add($collection,$data){
//        $manager = new MongoDB\Driver\Manager('mongodb://root:sjhc168@10.10.10.104:27017');
        $bulk = new MongoDB\Driver\BulkWrite; //默认是有序的，串行执行
//$bulk = new MongoDB\Driver\BulkWrite(['ordered' => flase]);//如果要改成无序操作则加flase，并行执行
        foreach($data as $v){
            $bulk->insert($v);
        }
        $this->Manager->executeBulkWrite($collection, $bulk);

    }


    //批量更新
    public function update($collection,$data){
        $bulk = new MongoDB\Driver\BulkWrite;
        foreach($data as $v){
            $bulk->update($v[0],$v[1],$v[2]);
        }
//        $bulk->update(
//            ['_id' => $id1],
//            ['$set' => ['status' => 100]],
//            ['multi' => false, 'upsert' => false]
//        );
//        $bulk->update(
//            ['_id' => $id2],
//            ['$set' => ['status' => 100]],
//            ['multi' => false, 'upsert' => false]
//        );
        $result = $this->Manager->executeBulkWrite($collection, $bulk);
        return $result;
    }
}
