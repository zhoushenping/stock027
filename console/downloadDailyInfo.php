<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

set_time_limit(0);

$symbols_all  = StockList::getSymbols(0);
$existSymbols = eastDailySummary::getExistSymbols(date('Ymd'));

$symbols_download = [];
foreach ($symbols_all as $symbol) {
    if (in_array($symbol, $existSymbols)) continue;
    $symbols_download[] = $symbol;
}

echo "共有" . count($symbols_all) . "只股票\n";
echo "已存在" . count($existSymbols) . "只股票\n";

$count = count($symbols_download);

echo "将要下载" . $count . "只股票的当日详情\n";

if ($count == 0) {
    die;
}

eastDailySummary::get($symbols_download);//下载各股的当日统计
