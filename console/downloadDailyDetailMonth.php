<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

set_time_limit(0);

$allsymbols = StockList::getSymbols(0);
$arr_date   = TradeDate::getTradeDates(60);

$each = 200;

foreach ($arr_date as $date) {
    if ($date >= 20170919) continue;
    if (!is_dir("/data/zhanmo/download/$date")) {
        $batch = 1;
        foreach (array_chunk($allsymbols, $each) as $arr_symbols) {
            $msg = time() . "开始下载{$date}第{$batch}组{$each}只股票的交易详单";
            echo "$msg\n";
            Log2::save_run_log($msg, 'downloadDailyDetail');
            DailyTradeDetail::downloadMulti($arr_symbols, [$date]);
            $batch++;
        }
    }

    foreach ($allsymbols as $symbol) {
        MinuteSummary::calculate($symbol, $date);
    }
}

