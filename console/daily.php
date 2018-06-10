<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

set_time_limit(0);

StockList::update();
eastStockInfo::getAll();
TradeDate::renewTradeDatesFromApi();
eastZhishu::refresh();//更新指数的每日信息
DBHandle::truncate(RealTime::table);
/////////////////每日下周各股的交易详单并生成分钟小计 begin//////////////////////////////////
$date = $argv[1];

if ($date == '') $date = date('Ymd');

$allsymbols = StockList::getSymbols(0);
$arr_date   = [$date];

$each  = 100;
$batch = 1;
foreach (array_chunk($allsymbols, $each) as $arr_symbols) {
    echo time() . "下载当天各股每分钟数据,第{$batch}组,每组{$each}个\n";
    eastMinuteSummary::get($arr_symbols);
    $batch++;
}

$each  = 200;
$batch = 1;
foreach (array_chunk($allsymbols, $each) as $arr_symbols) {
    $msg = time() . "开始下载{$date}第{$batch}组{$each}只股票的交易详单";
    echo "$msg\n";
    Log2::save_run_log($msg, 'downloadDailyDetail');
    DailyTradeDetail::downloadMulti($arr_symbols, $arr_date);
    $batch++;
}
/////////////////每日下周各股的交易详单并生成分钟小计 end  //////////////////////////////////
