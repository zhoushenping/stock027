<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

set_time_limit(0);

$symbols  = ['sh600340'];
$arr_date = TradeDate::getTradeDates(60);

DailyTradeDetail::downloadMulti($symbols, $arr_date);

foreach ($arr_date as $date) {
    foreach ($symbols as $symbol) {
        MinuteSummary::calculate($symbol, $date);
    }
}

