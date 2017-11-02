<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/18
 * Time: 下午10:04
 */
class Calendar
{

    static function getMonthlyDates($dateCount = 365)
    {
        for ($i = $dateCount - 1; $i >= 0; $i--) {
            $time          = strtotime("-$i days");
            $month         = date('Y-m', $time);
            $date          = date('Y-m-d', $time);
            $ret[$month][] = $date;
        }

        return $ret;
    }

    static function isDateBefore($d0, $d1)
    {
        $t0 = strtotime($d0);
        $t1 = strtotime($d1);

        return $t0 < $t1;
    }
}
