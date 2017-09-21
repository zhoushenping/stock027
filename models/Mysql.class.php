<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/29
 * Time: 17:20
 */
class Mysql
{
    static function makeInsertString($arr)
    {
        $tmp = array();
        foreach ($arr as $k => $v)
        {
            if (is_numeric($v)) $tmp[] = "`$k`=$v";
            if (!is_numeric($v))
            {
                $v     = addslashes($v);
                $tmp[] = "`$k`='$v'";
            }
        }

        return implode(',', $tmp);
    }

    static function makeWhereFromArray($arr = array())
    {
        if (empty($arr)) return 1;

        return implode(" AND ", $arr);
    }
}