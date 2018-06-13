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
$date_e = 20180612;

$fenhongSymbols = [];//指定日期范围内有分红的股票的代码
foreach (eastFenhong::queryFromOnline() as $symbol => $arr) {
    foreach ($arr as $date => $null) {
        if ($date_s <= $date) {
            $fenhongSymbols[$symbol] = 1;
        }
    }
}

$lastInfo = eastLast::getLastInfo();
$where    = "`date`>=$date_s AND `date`<=$date_e ORDER BY `symbol`,`date`";
$rs       = DBHandle::select(DailySummary::table, $where, "`date`,`symbol`,`low`,`high`");
$temp     = [];

foreach ($rs as $i => $item) {
    $columns = ['high', 'low'];
    if (isset($fenhongSymbols[$item['symbol']])) {
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

    $shangpo_max = Number::getDiffRate($low1, $top) * -1;
    $xiapo_max   = Number::getDiffRate($low2, $top) * -1;
    $xiapo_now   = Number::getDiffRate($price_now, $top) * -1;
    $bonusSpace  = Number::getPercent($top - $price_now, $top - $low1, 2);//盈利空间

    if ($xiapo_max < 5) continue;//过滤掉上下势头不够强烈的
    if ($xiapo_now < 4) continue;//过滤掉上下势头不够强烈的

    if ($bonusSpace < 50) continue;//盈利空间需要不低于50%
    if ($price_now < $low2 * 1.005) continue;//现价需要不低于之前的后谷价加千分之五
    if ($low_today <= $low2) continue;//今天最低价需要不低于之前的后谷价
//    if ($price_now < $low_today * 1.005) continue;//现价需要不低于今天最低价加千分之五
    if (strpos($symbol, 'sz30') === 0) continue;

    if ($low1 == 0) continue;

    if ($low2 <= $low1) {
        continue;//过滤掉破位的
    }

    $new = [
        'top'         => $top,
        'topDate'     => $topDate,
        'low1'        => $low1,
        'low2'        => $low2,
        'shangpo_max' => $shangpo_max,//最大回调
        'xiapo_max'   => $xiapo_max,//最大回调
        'xiapo_now'   => $xiapo_now,//现价回调
        'bonusSpace'  => $bonusSpace,//盈利空间
    ];

    $info[] = array_merge($lastInfo[$symbol], $new);
}

function pailie($a, $b)
{
    if ($a['xiapo_now'] == $b['xiapo_now']) return 0;

    return ($a['xiapo_now'] > $b['xiapo_now']) ? -1 : 1;
}

usort($info, 'pailie');
