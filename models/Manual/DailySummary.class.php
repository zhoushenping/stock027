<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/11/26
 * Time: 上午11:57
 */
class ManualDailySummary
{

    static function fenxi()
    {
        $date   = 20171124;
        $symbol = 'sz002354';
        $file   = DailyTradeDetail::downloadDir . "$date/{$symbol}.xls";

        var_dump(file($file));
    }
}
