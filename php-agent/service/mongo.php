<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/10
 * Time: 17:50
 */

class Mongo{
    const Db = 'myteahouse';
    public $Manager = null;
    public function __construct($info){
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
        $filter =  ['user_id'=>['$gt'=>0]]; //查询条件 user_id大于0
        $filter = [];
        $options = [
            'projection' => ['_id' => 1,'name'=>1], //不输出_id字段
//    'sort' => ['user_id'=>-1] //根据user_id字段排序 1是升序，-1是降序
        ];
        $query = new MongoDB\Driver\Query($filter, $options); //查询请求
        $list =$this->Manager->executeQuery(self::Db.'.'.$collection,$query)->toArray(); // 执行查询 location数据库下的box集合
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
    public function add($collection,$data){
//        $manager = new MongoDB\Driver\Manager('mongodb://root:sjhc168@10.10.10.104:27017');
        $bulk = new MongoDB\Driver\BulkWrite; //默认是有序的，串行执行
//$bulk = new MongoDB\Driver\BulkWrite(['ordered' => flase]);//如果要改成无序操作则加flase，并行执行
        $bulk->insert(['user_id' => 2, 'real_name'=>'中国',]);
        $bulk->insert(['user_id' => 3, 'real_name'=>'中国人',]);
        $this->manager->executeBulkWrite('location.box', $bulk);

    }
}
