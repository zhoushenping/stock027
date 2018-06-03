<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/5/24
 * Time: 上午11:45
 */
//ini_set("memory_limit", "500M");
$levelList = [
    0,
    50,
    300,
    1000,
    3000,
    30000,
];

function getLevel($num)
{
    $ret = 0;
    global $levelList;
    foreach ($levelList as $k => $v) {
        if ($num >= $v) $ret = $k;
    }

    return $ret + 1;
}

$amp_last = [];
$infoList = [];
$statis   = [];

$topList   = eastDailySummary::getTopList();
$stockInfo = eastStockInfo::readAllRecord();
$lastInfo  = eastLast::getAllRecords();

$today = $lastInfo[0]['date'];
foreach ($lastInfo as $item) {
    $amp_last[$item['symbol']] = $item['amp'];
}

foreach ($topList as $item) {
    if (TradeDate::isTradeDate($item['date']) == false) continue;

    $maketValue = $item['last'] * $stockInfo[$item['symbol']]['totalcapital'] * 10000;//当天市值
    $maketValue = Number::getYi($maketValue);
    $level      = getLevel($maketValue);

    $statis[$item['date']][$level]++;

    $item['mv']                        = $maketValue;
    $item['amp_last']                  = $amp_last[$item['symbol']];
    $infoList[$item['date']][$level][] = $item;
    $infoList[$item['date']][0][]      = $item;
}

if ($today && !isset($infoList[$today])) {
    foreach ($lastInfo as $item) {
        if (TradeDate::isTradeDate($item['date']) == false) continue;
        if ($item['amp'] < 9.9) continue;

        $maketValue = $item['trade'] * $stockInfo[$item['symbol']]['totalcapital'] * 10000;//当天市值
        $maketValue = Number::getYi($maketValue);
        $level      = getLevel($maketValue);

        $statis[$item['date']][$level]++;

        $item['amount']                    = $item['amount'] / 10000;//情况特殊
        $item['mv']                        = $maketValue;
        $item['amp_last']                  = $item['amp'];
        $infoList[$item['date']][$level][] = $item;
        $infoList[$item['date']][0][]      = $item;
    }
}

krsort($statis);

function getLevelStr($level)
{
    global $levelList;

    return $levelList[$level - 1] . "-" . $levelList[$level];
}

function pailie($a, $b)
{
    if ($a['amount'] == $b['amount']) return 0;

    return ($a['amount'] > $b['amount']) ? -1 : 1;
}



//usort($infoList, 'pailie');
