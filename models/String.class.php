<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/19
 * Time: 17:40
 */
class String
{
    //只留下数字
    static function _filterNoNumber(&$str)
    {
        $str = preg_replace('/\D/', '', $str);
    }

    //只留下数字
    static function filterNoNumber($str)
    {
        return preg_replace('/\D/', '', $str);
    }

    //只留下数字和字母
    static function _filterNoNormal(&$str)
    {
        $str = preg_replace('/\W/', '', $str);
        $str = str_replace('_', '', $str);
    }

    //只留下数字
    static function filterNoNormal($str)
    {
        $str = preg_replace('/\W/', '', $str);
        $str = str_replace('_', '', $str);

        return $str;
    }

    static function _getInt(&$str)
    {
        $ret = self::filterNoNumber($str);
        $str = (int)$ret;
    }

    static function getInt($str)
    {
        $ret = self::filterNoNumber($str);

        return (int)$ret;
    }

    static function utf_substr($str, $len)
    {
        for ($i = 0; $i < $len; $i++) {
            $temp_str = substr($str, 0, 1);
            if (ord($temp_str) > 127) {
                $i++;
                if ($i < $len) {
                    $new_str[] = substr($str, 0, 3);
                    $str       = substr($str, 3);
                }
            }
            else {
                $new_str[] = substr($str, 0, 1);
                $str       = substr($str, 1);
            }
        }

        return join($new_str);
    }

    static function utf_substrAdvanced($str, $len)
    {
        return (strlen($str) > $len) ? self::utf_substr($str, $len - 3) . '...' : $str;
    }

    static function replace_once($needle, $replace, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }

        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    static function getHiddenUname($uanme)
    {
        $ret = '';
        $len = mb_strlen($uanme);
        if ($len <= 4) {
            $ret = '****';
        }

        if ($len == 5) {
            $ret = '***' . mb_substr($uanme, -2);
        }
        if ($len == 6) {
            $ret = mb_substr($uanme, 0, 1) . '***' . mb_substr($uanme, -2);
        }
        if ($len == 7) {
            $ret = mb_substr($uanme, 0, 2) . '***' . mb_substr($uanme, -2);
        }
        if ($len == 8) {
            $ret = mb_substr($uanme, 0, 3) . '***' . mb_substr($uanme, -2);
        }
        if ($len >= 9) {
            $ret = mb_substr($uanme, 0, 3) . '***' . mb_substr($uanme, -3);
        }

        return $ret;
    }
}
