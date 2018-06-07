<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/6/6
 * Time: 上午11:37
 */

/*从5月30日起
找到这段时间内的最高价 top
如果在那之前和之后分别出现低价  low1  low2
以及现价now
now>low2>low1
top的日期需要大于low1的日期
*/

set_time_limit(0);
ini_set("memory_limit", "500M");

$date_s = 20180530;
$date_e = 20180606;

$fenhongSymbols = [];//指定日期范围内有分红的股票的代码
foreach (eastFenhong::queryFromOnline() as $symbol => $arr) {
    foreach ($arr as $date => $null) {
        if ($date_s <= $date) {
            $fenhongSymbols[] = $symbol;
        }
    }
}

$lastInfo = eastLast::getLastInfo();
$where    = "`date`>=$date_s AND `date`<=$date_e ORDER BY `symbol`,`date`";
$rs       = DBHandle::select(DailySummary::table, $where, "`date`,`symbol`,`low`,`high`");
$temp     = [];

foreach ($rs as $i => $item) {
    $columns = ['open', 'yesterday', 'high', 'low', 'last'];
    if (in_array($item['symbol'], $fenhongSymbols)) {
        foreach ($columns as $col) {
            $item[$col] = eastFenhong::getLaterPrice($item['symbol'], $item[$col], $item['date']);
            $item[$col] = Number::getFloat($item[$col], 2);
        }
    }

    $temp[$item['symbol']][] = $item;
}

foreach ($temp as $symbol => $items) {
    $topDate = 0;
    $top     = 0;
    foreach ($items as $item) {
        if ($item['high'] >= $top) {
            $top     = $item['high'];
            $topDate = $item['date'];
        }
    }

    $low1 = 0;
    foreach (array_reverse($items) as $item) {
        if ($item['date'] >= $topDate) continue;

        if ($low1 == 0 || $item['low'] <= $low1) {
            $low1 = $item['low'];
        }
    }

    $low2 = 0;
    foreach ($items as $item) {
        if ($item['date'] < $topDate) continue;

        if ($low2 == 0 || $item['low'] <= $low2) {
            $low2 = $item['low'];
        }
    }

    $price_now = $lastInfo[$symbol]['trade'];
    $low_today = $lastInfo[$symbol]['low'];

    $huitiao_max = Number::getDiffRate($low2, $top);
    $huitiao_now = Number::getDiffRate($price_now, $top);
    $bonusSpace  = Number::getPercent($top - $price_now, $top - $low1, 2);//盈利空间

    if ($huitiao_max > -5) continue;//过滤掉上下势头不够强烈的
    if ($huitiao_now > -4) continue;//过滤掉上下势头不够强烈的

    if ($bonusSpace < 60) continue;//盈利空间需要不低于60%
    if ($price_now < $low2) continue;//现价需要不低于之前的后谷价
    if ($low_today < $low2) continue;//今天最低价需要不低于之前的后谷价

    if ($low1 == 0) continue;

    if ($low2 <= $low1) {
        continue;//过滤掉破位的
    }

    $new = [
        'top'         => $top,
        'topDate'     => $topDate,
        'low1'        => $low1,
        'low2'        => $low2,
        'huitiao_max' => $huitiao_max,//最大回调比
        'huitiao_now' => $huitiao_now,//现价回调比
        'bonusSpace'  => $bonusSpace,//盈利空间
    ];

    $info[] = array_merge($lastInfo[$symbol], $new);
}

function pailie($a, $b)
{
    if ($a['huitiao_max'] == $b['huitiao_max']) return 0;

    return ($a['huitiao_max'] < $b['huitiao_max']) ? -1 : 1;
}

usort($info, 'pailie');
