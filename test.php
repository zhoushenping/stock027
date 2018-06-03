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

//var_dump(DailyTradeDetail::getDailyDetail('sh600000', 20171010));

//Zhuli::downloadDailyTop50(TradeDate::getLastDates(90));
//Zhuli::downloadGegu(['sz002467', 'sh601288'], 3);

var_dump(eastDailySummary::getLastAverageForAll(5));
