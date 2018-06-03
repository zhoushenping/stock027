<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/5/31
 * Time: 下午4:09
 */
class eastBill
{

    const table = 'eastBill';

    static function insert($amount, $date)
    {
        DBHandle::MyInsert(self::table, "`amount`='$amount',`date`='$date'");
    }

    static function get()
    {
        return DBHandle::select(self::table);
    }
}
