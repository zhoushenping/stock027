<?php

class Random
{

    static $str_normal = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    static $str_verify = 'abcdefghkmnpqrstuvwxyzZ23456789';//确认码专用,去掉了容易混淆的字符和大写字符
    static $str_number = '0123456789';

    public static function getRandom($length = 10)
    {
        return self::call(self::$str_normal, $length);
    }

    public static function getRandomNumber($length = 10)
    {
        return self::call(self::$str_number, $length);
    }

    public static function getVerifyCode($length = 4)
    {
        return self::call(self::$str_verify, $length);
    }

    private static function call($str, $length)
    {
        $arr = self::_explode($str);
        shuffle($arr);
        $arr = array_slice($arr, 0, $length);

        return implode('', $arr);
    }

    private static function _explode($str)
    {
        $ret = array();
        for ($i = 0; $i < strlen($str); $i++) {
            $ret[] = substr($str, $i, 1);
        }

        return $ret;
    }

    public static function isRandom($str, $length = 10)
    {
        return strlen($str) == $length;
    }
}
