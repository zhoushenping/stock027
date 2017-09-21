<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/12/25
 * Time: 10:46
 */
class Time
{

    static function getAppDisplayTime()
    {
        $timezone_before  = date_default_timezone_get();
        $app_display_date = date('Y-m-d H:i:s');
        date_default_timezone_set('UTC');
        $app_display_time = strtotime($app_display_date);
        date_default_timezone_set($timezone_before);

        return $app_display_time;
    }

    //获得两个时间戳之间的不活跃天数  勿随意修订
    static function getSleepDays($time1, $time2 = 0)
    {
        global $_CURRENT_TIME;
        if ($time2 == 0) {
            $time2 = $_CURRENT_TIME;
        }

        $time01 = self::getTime0($time1);
        $time02 = self::getTime0($time2);

        $daysInterval = abs(($time02 - $time01) / 86400);

        return ($daysInterval <= 1) ? 0 : ((int)$daysInterval - 1);
    }

    //获得给定时间戳当天0点整的时间戳
    static function getTime0($time)
    {
        $date = date('Y-m-d', $time);

        return strtotime("$date 00:00:00");
    }

    static function getDateFirstSecond($date)
    {
        return strtotime("$date 00:00:00");
    }

    static function getDateLastSecond($date)
    {
        return strtotime("$date 23:59:59");
    }

    static function checkDateIsValid($date, $formats = array("Y-m-d", "Y/m/d"))
    {
        $unixTime = strtotime($date);
        //strtotime转换不对，日期格式显然不对。
        if (!$unixTime) {
            return false;
        }
        //校验日期的有效性，只要满足其中一个格式就OK
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }

        return false;
    }

    static function getTimeFromHour($str)
    {
        if ($str == '') {
            return 0;
        }
        $a = strlen('2016-05-04 01:35:59') - strlen($str);
        if ($a == 6 || $a == 7) {
            $str .= ':00:00';
        }
        if ($a == 9) {
            $str .= ' 00:00:00';
        }

        return strtotime($str);
    }

    static function getDateDiff($date1, $date2 = 0)
    {
        if ($date2 == 0) {
            $date2 = date('Y-m-d');
        }

        $t1 = strtotime($date1);
        $t2 = strtotime($date2);

        return ($t1 - $t2) / 86400;
    }

    static function makeCalendar($time0 = 0, $time1 = 0)
    {
        $ret = array();
        if ($time0 == 0) {
            $time0 = strtotime('-1 year');
        }
        if ($time1 == 0) {
            $time1 = strtotime('+1 year');
        }
        $month0 = date('Ym', $time0);
        $month1 = date('Ym', $time1);

        for ($i = (int)$month0; $i <= (int)$month1; $i++) {
            if ((int)(substr($i, -2)) == 0 || (int)(substr($i, -2)) > 12) {
                continue;
            }

            $m       = substr($i, 0, 4) . '-' . substr($i, -2);
            $ret[$m] = self::getMonthlyDate($m);
        }

        return $ret;
    }

    static function getMonthlyDate($month = '')
    {
        $ret = array();
        if ($month == '') {
            $month = date('Y-m');//输入值需要是这个结构
        }

        for ($i = 1; $i <= date('t', strtotime("{$month}-01")); $i++) {
            $ret[] = $i < 10 ? "{$month}-0{$i}" : "{$month}-{$i}";
        }

        return $ret;
    }

    static function getCurrentTime()
    {
        $ret = time();
        if (DEVELOP_MODE) {
            if (!empty($_REQUEST['time'])) {
                $_SESSION['time'] = strtotime($_REQUEST['time']);
            }
            $ret = $_SESSION['time'] ? $_SESSION['time'] : time();
        }

        return $ret;
    }
}
