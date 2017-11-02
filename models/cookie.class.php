<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/7
 * Time: 15:52
 */
class cookie
{

    static function setOasCookie($key, $val, $days = 1, $dir = "/")
    {
        $t = ($days == 0) ? 0 : time() + 3600 * 24 * $days;
        setcookie($key, $val, $t, $dir, OAS_DOMAIN);
    }

    static function deleteOasCookie($key, $val = 0, $days = -1, $dir = "/")
    {
        setcookie($key, $val, time() + 3600 * 24 * $days, $dir, OAS_DOMAIN);
    }

    static function parseCookieStr($str)
    {
        foreach (explode('; ', $str) as $item) {
            list($k, $v) = explode('=', $item);
            $ret[$k] = urldecode($v);
        }

        return $ret;
    }

    static function makeCookieStr($arr)
    {
        foreach ($arr as $k => $v) {
            $temp[] = "$k=" . urlencode($v);
        }

        return implode('; ', $temp);
    }
}
