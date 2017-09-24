<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/20
 * Time: 下午11:54
 */
set_time_limit(300);
ini_set('memory_limit', '128M');

include_once(dirname(__FILE__) . '/define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

//var_dump(TradeDate::getTradeDates());

//DailySummary::getAll();

//RealTime::getAll();

//DailyTradeDetail::getMultiDetail(['sz002354'], ['2017-09-22',]);
var_dump(MinuteSummary::calculate('sz002354', 20170922));
die;


