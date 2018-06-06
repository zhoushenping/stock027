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
$lastInfo = eastLast::getLastInfo();
$where    = "`date`>=20180530 AND `date`<=20180605 ORDER BY `symbol`,`date`";
$rs       = DBHandle::select(DailySummary::table, $where, "`date`,`symbol`,`low`,`high`");
$temp     = [];

foreach ($rs as $item) {
    $temp[$item['symbol']][] = $item;
}

foreach ($temp as $symbol => $items) {
//    if ($symbol != 'sh601100') continue;
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

    $huitiao = Number::getDiffRate($low2, $top);

    if ($huitiao > -4) continue;//过滤掉上下势头不够强烈的

    if ($low1 == 0) continue;

    if ($low2 <= $low1) {
        continue;//过滤掉破位的
    }

    $new = [
        'top'     => $top,
        'topDate' => $topDate,
        'low1'    => $low1,
        'low2'    => $low2,
        'huitiao' => $huitiao,
    ];

    $info[] = array_merge($lastInfo[$symbol], $new);
}

function pailie($a, $b)
{
    if ($a['huitiao'] == $b['huitiao']) return 0;

    return ($a['huitiao'] < $b['huitiao']) ? -1 : 1;
}

usort($info, 'pailie');
