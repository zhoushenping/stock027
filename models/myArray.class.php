<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/20
 * Time: 12:38
 */
class myArray
{
    static function get($arr, $num)
    {
        $ret = array();
        $n   = 0;
        foreach ($arr as $k => $v)
        {
            if ($n < $num) $ret[$k] = $v;
            $n++;
        }

        return $ret;
    }

    static function trimSameItem($arr1, $arr2)
    {
        $ret = array();
        foreach ($arr1 as $k1 => $v1)
        {
            if ($v1 != $arr2[$k1]) $ret[$k1] = $v1;
        }

        return $ret;
    }

    /*
     * 功能：按照权重 返回随机值
     * 输入样本 $arr = array('a' => 20, 'b' => 30, 'c' => 15, 'd' => 35);//key为待选项，value为权重值
     */
    static function getRandByWeight($arr)
    {
        $temp = array();

        foreach ($arr as $k => $v)
        {
            for ($i = 0; $i < $v; $i++)
            {
                $temp[] = $k;//插入权重值个数的key
            }
        }

        return $temp[array_rand($temp, 1)];
    }

    static function trimEmptyUid($uids)
    {
        $ret = array();
        foreach ($uids as $uid)
        {
            $uid = String::filterNoNumber($uid);
            if ($uid != '') $ret[] = $uid;
        }

        return $ret;
    }

    //获取给定数组中  小于或等于给定值的  最接近于给定值的数
    static function getNearestVal($arr, $val)
    {
        $ret = array();

        foreach ($arr as $i)
        {
            if ($i <= $val) $ret[] = $i;
        }

        rsort($ret);

        return !empty($ret) ? $ret[0] : false;
    }
}