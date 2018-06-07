<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2016/3/15
 * Time: 12:26
 */
class URL
{

    static function getChartUrl($symbol)
    {
        return './?a=sina&m=clear_chart&type=simple&symbol=' . StockList::getStandardSymbol($symbol);
    }
}
