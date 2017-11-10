<?php
$type              = $_REQUEST['type'];
$iframe_url_common = '//stockpage.10jqka.com.cn/HQ_v4.html#hs_';

$priority = eastPriority::get();

$records      = eastHistory::getRecords();
$records_show = [];
$symbols_omit = [];
foreach ($records as $item) {
    $symbol = $item['Zqdm'];
    if ($type == 'now' && $item['Gfye'] == 0) {
        $symbols_omit[] = $symbol;
    }

    if (in_array($symbol, $symbols_omit)) continue;
    $records_show[] = $item;
    $symbols_omit[] = $symbol;
}

/*加上优先级 并排序 begin*/
foreach ($records_show as $k => $item) {
    $records_show[$k]['priority'] = (int)$priority[$item['Zqdm']];
}

function cmp($a, $b)
{
    if ($a['priority'] == $b['priority']) {
        return 0;
    }

    return ($a['priority'] > $b['priority']) ? -1 : 1;
}

usort($records_show, "cmp");
/*加上优先级 并排序 end*/
