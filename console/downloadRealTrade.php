<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

ini_set("memory_limit", "500M");

$d = date('Ymd');

$w = date('w', time());//w=0 周日    w=6 周六
if ($w == 0 || $w == 6) die;//周六日不交易   不下载数据
if (in_array($d, TradeDate::$plus_holidays)) die;//额外不交易日期   不下载数据

if (Time::isTradeTime()) {
    $t = microtime(true);
    Log2::save_run_log($t, 'real');

    //下载实时交易信息
    RealTime::getAll();

    $t = microtime(true);
    Log2::save_run_log($t, 'real');
}

