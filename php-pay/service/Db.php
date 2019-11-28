<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/12
 * Time: 17:27
 */

final class Db{
    private $charset = "utf8";         //字符串编码
    private $link = NULL;              //数据库连接
    public static $instance = NULL;   //存储对象

    //初始化
    private function __construct($datebase){

        $hostfield=config('dbhost');
        $userfield=config('dbuser');
        $password=config('dbpass');
        $port=config('dbport');
        $this->link = mysqli_connect($hostfield,$userfield,$password,$datebase,$port);
        if(!$this->link){
            echo json_encode(['state'=>-1,'msg'=>'pay error code:44']) ;exit;
//            die("连接失败: " . mysqli_connect_error());
        }
        mysqli_query($this->link,"SET fieldS ".$this->charset);
        return $this->link;
    }

    //防止克隆
    private function __clone(){}

    //静态公共接口
    public static function getInstance($datebase){
        if(!self::$instance instanceof self){
            self::$instance = new DB($datebase);
        }

        return self::$instance;
    }

    /*
     *describe:查询单个字段
     *@param $field        type:string  传入需要筛选的字段
     *@param $table_name   type:string  需要查询的表名
     *@param $where        type:array   需要筛选的where条件
    */
    public function getField($field="",$table_name="",$where=""){
        $where_string = "";
        //where条件
        $where_string = $this->getWhere($where);
        if(is_string($field)){
            //组装sql
            $sql = "SELECT {$field} FROM {$table_name} WHERE {$where_string}";
            $result = mysqli_query($this->link,$sql);
            while($res = mysqli_fetch_array($result,MYSQL_ASSOC)){
                $info[] = $res;
            }
            //若只有一个数组，则返回一维数组
            if(count($info)==1){
                return $info[0];
            }else{
                return $info;
            }
        }

    }

    /*
     *describe:查询单条语句
     *@param $sql 查询语句
     *@param $type 返回数组的类型
     *        MYSQL_ASSOC - 关联数组(默认)
     *        MYSQL_NUM - 数字数组
     *        MYSQL_BOTH -同时产生关联和数字数组
    */
    public function getRow($sql){
        $result = mysqli_query($this->link,$sql);
        $res = mysqli_fetch_assoc($result);
        return $res;
    }

    /*
     *describe:查询多条语句
     *@param $sql 查询语句
     *@param $type 返回数组的类型
     *        MYSQL_ASSOC - 关联数组(默认)
     *        MYSQL_NUM - 数字数组
     *        MYSQL_BOTH -同时产生关联和数字数组
    */
    public function getRows($sql){
        $result = mysqli_query($this->link,$sql);
        while($rows = mysqli_fetch_assoc($result)){
            $res[] = $rows;
        }
        return $res;
    }

    /*
     *describe:增加语句
     *@param type:array or string $info 传入的值
                 若为数组则进行拼接SQL语句，并新增 返回新增的ID
                 若传入的为SQL语句则直接执行，返回新增的ID
     *@param $table_name  为需要新增的表名(可选)
    */
    public function add($info,$table_name = ""){
        //判断是否为数组，进行SQL拼接
        if(is_array($info)){
            $field = "";
            $val ="";
            $fields = "";
            $vals ="";
            foreach($info as $k=>$v){
                $field .= "`".$k."`,";
                $val .= "'".$v."',";
            }
            //$fields =  substr($field,0,strlen($field)-1);
            //$vals = substr($val,0,strlen($val)-1);
            $fields = trim($field,",");
            $vals = trim($val,",");
            $sql = "INSERT INTO {$table_name} ({$fields}) VALUES ({$vals})";
            if(mysqli_query($this->link,$sql)){
                return mysqli_insert_id($this->link);
            }else{
                echo "error:".$sql."<br/>".mysqli_error($this->link);
            }
            //判断是否为SQL语句
        }else if(is_string($info)){
            if(mysqli_query($this->link,$info)){
                return true;
            }else{
                return['error'=>"error:".$info."<br/>".mysqli_error($this->link)] ;
            }
        }else{
            return ['error'=>'param is faild'];
        }
    }

    /*
     *describe:更新
     *@param type:array            $info       传入的修改的数组
     *@param type:string           $table_name 需修改的表名
     *@param type:array            $where      修改的where条件,传入数组
    */
    public function update($sql){
        mysqli_query($this->link,$sql);
        if( mysqli_affected_rows($this->link)){
            return 1;
        }else{
            return 0;
        }
        //判断是否是数组,进行SQL拼接
//        if(is_array($info)){
//            $field = "";
//            $fields = "";
//            //拼接修改语句
//            foreach($info as $k=>$v){
//                $field .= "`{$k}`='{$v}',";
//            }
//            $fields = trim($field,",");
//            //拼接where条件
//            $where_string = "";
//            $where_string = $this->getWhere($where);
//            //拼接SQL
//            $sql = "UPDATE {$table_name} SET {$fields} where {$where_string}";
//            echo $sql;
//            die();
//            if(mysqli_query($this->link,$sql)){
//                return mysqli_affected_rows($this->link);
//            }else{
//                echo "error:".$sql."<br/>".mysqli_error($this->link);
//            }
//        }else{
//            echo "error";
//        }
    }

    /*
     *describe:删除
     *@param $where        type:array   传入需要删除的where条件  为数组
     *@param $table_name   type:string  需要删除的表名
    */
    public function del($where,$table_name=""){
        if(is_array($where)){
            $where_string ="";
            //拼接where条件
            $where_string = $this->getWhere($where);
            $sql = "DELETE FROM {$table_name} WHERE {$where_string}";
            if(mysqli_query($this->link,$sql)){
                return mysqli_affected_rows($this->link);
            }else{
                echo "error:".$sql."<br/>".mysqli_error($this->link);
            }
        }else{
            echo "error";
        }
    }

    /*
     *describe:执行原生sql
     *@param $sql  type:string 需要执行的sql
    */
    public function query($sql){
        if(mysqli_query($this->link,$sql)){
            return mysqli_affected_rows($this->link);
        }else{
            echo "error:".$sql."<br/>".mysqli_error($this->link);
        }
    }

    /*
     *describe:拼接where条件
     *@param $where type:array  传入where为数组的条件
     */
    private function getWhere($where){
        $where_string = "";
        if(is_array($where)){
            foreach($where as $k=>$v){
                if(is_array($v)){
                    foreach($v as $kk=>$vv){
                        $where_string .= "`{$k}` {$kk} '{$vv}' AND ";
                    }
                }else{
                    $where_string .= "`{$k}`='{$v}' AND ";
                }
            }
        }
        return rtrim($where_string,"AND ");
    }
}
