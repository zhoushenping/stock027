<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/23
 * Time: 下午6:01
 */
class Number
{

    //$decimals=小数位数
    static function getPercent($num1, $num2, $decimals = 2)
    {
        if ($num2 == 0) return false;

        return number_format($num1 * 100 / $num2, $decimals);
    }
}
