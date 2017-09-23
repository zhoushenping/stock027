<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/23
 * Time: 下午6:23
 * 关于交易日的逻辑代码请尽量放在这个类里面
 */
class TradeDate
{

    //其他指定节假日
    static $holidayPlus = [
        '20171001',
        '20171002',
        '20171003',
        '20171004',
        '20171005',
        '20171006',
        '20171007',
        '20171008',
    ];

    //获取最近n天内的的交易日
    static function getTradeDates($count = 365)
    {
        $ret = [];

        foreach (self::getLastDates($count) as $d) {
            if (self::isTradeDate($d)) $ret[] = $d;
        }

        return $ret;
    }

    static function getLastDates($count = 365)
    {
        $ret = [];
        $now = time();
        for ($i = 0; $i <= $count; $i++) {
            $ret[] = date('Ymd', $now - $i * 86400);
        }

        return $ret;
    }

    static function isTradeDate($date)
    {
        $ret = true;
        $w   = date('w', strtotime($date));//w=0 周日    w=6 周六
        if ($w == 0 || $w == 6) $ret = false;//周六日不交易
        if (in_array($date, self::$holidayPlus)) $ret = false;//其他指定节假日不交易

        return $ret;
    }
}
