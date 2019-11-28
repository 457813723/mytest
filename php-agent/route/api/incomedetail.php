<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/17
 * Time: 18:52
 */
//收益详情
route::post('api/incomedetail','incomedetail@index');
//deposit_record
//提现记录
route::post('api/deposit_record','incomedetail@deposit_record');
//提现
route::post('api/distribute_to_games','incomedetail@distribute_to_games');