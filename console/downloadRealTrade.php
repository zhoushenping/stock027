<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

$d = date('Y-m-d');

if (TradeDate::isTradeDate($d) && Time::isTradeTime()) {
    $t = microtime(true);
    Log2::save_run_log($t, 'real');

    //下载实时交易信息
    RealTime::getAll();

    $t = microtime(true);
    Log2::save_run_log($t, 'real');
}

