<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

set_time_limit(0);

$date = $argv[1];

if ($date == '') $date = date('Y-m-d');

$allsymbols = DailySummary::getSymbols(0);
$arr_date   = [$date];

$each  = 200;
$batch = 1;
foreach (array_chunk($allsymbols, $each) as $arr_symbols) {
    $msg = time() . "开始下载第{$batch}组{$each}只股票的交易详单";
    echo "$msg\n";
    Log2::save_run_log($msg, 'downloadDailyDetail');
    DailyTradeDetail::getMultiDetail($arr_symbols, $arr_date);
    $batch++;
}

