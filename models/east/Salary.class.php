<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/19
 * Time: 下午5:11
 * 营收计算类
 */
class eastSalary
{

    static function getChicang($uid, $t, $symbol)
    {
        $chicang = [];
        $rs      = eastHistory::getRecords($uid);
        foreach ($rs as $item) {
            if ($item['time'] >= $t) continue;
            if ($item['Zqdm'] != $symbol) continue;
            if (!isset($chicang[$symbol])) $chicang[$symbol] = $item['Gfye'];
        }

        return (int)($chicang[$symbol]);
    }

    static function getTradeNetIncome($uid, $t0 = 0, $t1 = 0, $symbols = [])
    {
        if (!empty($symbols) && !is_array($symbols)) {
            $symbols = [$symbols];
        }
        if ($t1 == 0) $t1 = time();
        $ret = [];
        $rs  = eastHistory::getRecords($uid);
        foreach ($rs as $item) {
            if ($item['time'] < $t0 || $item['time'] > $t1) continue;
            if ($item['Zqdm'] == '') continue;
            if (!in_array($item['Zqdm'], $symbols)) continue;
            $ret[$item['Zqdm']] += $item['Zjfss'];
        }

        return $ret;
    }

    static function getPrice($symbol, $t)
    {
        $t          = $t - 1;
        $price_real = RealTime::getPrice($symbol, $t);
        $price_his  = eastMinuteSummary::getPrice($symbol, $t);

        return $price_real != 0 ? $price_real : $price_his;
    }
}
