<?php
//
//require 'lib.php';
//require './MongoService.php';
//$mongo = new MongoService();
//$collection = "2019_05_26.fishcoinlog";//数据库.表明

//批量更新
//$id1 = '5cea951a0f831a264a6f5671';
//$id1  = new \MongoDB\BSON\ObjectId($id1);
//$id2 = '5cea95160f831a264a6f5657';
//$id2  = new \MongoDB\BSON\ObjectId($id2);
//$data = [
//    [
//        ['_id' => $id1],
//        ['$set' => ['status' => 11]],
//        ['multi' => false, 'upsert' => false]
//    ],
//    [
//        ['_id' => $id2],
//        ['$set' => ['status' => 11]],
//        ['multi' => false, 'upsert' => false]
//    ]
//];
//
//
//$r = $mongo->update($collection,$data);
//var_dump($r);exit;
//echo intval('v');exit;
//echo 'c' % 3;exit;
//$id  = new \MongoDB\BSON\ObjectId("5cea55500f831a264a6e17ea");
//$filter = [
//    'time_stamp'=>['$mod'=>[3,0]],
//    'status'=>['$ne'=>1]
//];
//$options = [
//    'projection' => [], //选需要的字段，id是默认的
////    'sort' => ['user_id'=>-1] //根据user_id字段排序 1是升序，-1是降序
//];
//$res = $mongo->query($collection,$filter,$options);
//var_dump($res);exit;







/**
 * run with command
 * php start.php start
 */

ini_set('display_errors', 'on');
use Workerman\Worker;

if(strpos(strtolower(PHP_OS), 'win') === 0)
{
    exit("start.php not support windows, please use start_for_win.bat\n");
}

// 检查扩展
if(!extension_loaded('pcntl'))
{
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

if(!extension_loaded('posix'))
{
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

// 标记是全局启动
define('GLOBAL_START', 1);

require_once __DIR__ . '/vendor/autoload.php';

// 加载所有Applications/*/start.php，以便启动所有服务
foreach(glob(__DIR__.'/Applications/daqucount/start*.php') as $start_file)
{
    require_once $start_file;
}
ini_set('date.timezone','Asia/Shanghai');
require 'lib.php';
// 运行所有服务
Worker::runAll();
