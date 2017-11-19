<?

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
    $analyse_result[getArea($item['amp'])]++;
}

krsort($analyse_result);

$total = array_sum($analyse_result);

$max = max($analyse_result);
