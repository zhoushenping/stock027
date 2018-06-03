<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/6/3
 * Time: 上午10:25
 */

/*
 * 当前涨幅超过9%的股票
过滤掉其中  前面10天中  日涨幅超过8%的天数大于or等于2的
 *
 * */

$config = [
    'amp_last_r'  => 9,
    'days_r'      => 10,
    'amp_limit'   => 9,
    'count_limit' => 2,
];

foreach ($config as $k => $v) {
    if (!empty($_REQUEST[$k])) {
        $$k           = $_REQUEST[$k];
        $$k           = (float)$$k;
        $_SESSION[$k] = $$k;
    }
    else if (!empty($_SESSION[$k])) {
        $$k = $_SESSION[$k];
    }
    else {
        $$k = $v;
    }
}

$infoList = [];

$lastInfo = eastLast::getLastInfo();

$aa   = TradeDate::getTradeDates($days_r);
$date = $aa[count($aa) - 1];

$rs   = DBHandle::select(DailySummary::table, "`date`>=$date and `amp`>$amp_limit group by symbol",
                         "symbol,count(*) as c");
$temp = [];
foreach ($rs as $item) {
    if ($lastInfo[$item['symbol']]['amp'] < $amp_last_r) continue;

    if ($item['c'] >= $count_limit) continue;

    $item       = array_merge($lastInfo[$item['symbol']], $item);
    $infoList[] = $item;
}
