<?
$marketValue_low_r  = (int)$_REQUEST['marketValue_low'];
$marketValue_high_r = (int)$_REQUEST['marketValue_high'];
$syl_low_r          = (int)$_REQUEST['syl_low'];
$syl_high_r         = (int)$_REQUEST['syl_high'];

if ($marketValue_high_r == 0) $marketValue_high_r = 30000;
if ($syl_high_r == 0) $syl_high_r = 50;

$analyse_result = [];
function getArea($amp)
{
    $ret = floor($amp);
    if ($ret == -11) $ret = -10;
    if ($ret == 10) $ret = 9;

    return $ret;
}

foreach (eastBigSale::readAll() as $item) {
    if ($item['trade'] == 0) continue;

    if ($item['marketValue'] < $marketValue_low_r) continue;
    if ($item['marketValue'] > $marketValue_high_r) continue;
    if ($item['syl'] < $syl_low_r) continue;
    if ($item['syl'] > $syl_high_r) continue;
    $analyse_result[getArea($item['amp'])]++;
}

krsort($analyse_result);

$total = array_sum($analyse_result);

$max = max($analyse_result);
