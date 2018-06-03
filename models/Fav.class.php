<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/5/30
 * Time: 下午4:48
 */
class Fav
{

    const table = 'fav';

    static function add($uid, $symbol)
    {
        $arr_data      = [];
        $arr_columns[] = 'uid';
        $arr_columns[] = 'symbol';

        $arr_data[] = [
            $uid,
            $symbol,
        ];

        DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
    }

    static function delete($uid, $symbol)
    {
        $where = [
            "`uid`='$uid'",
            "`symbol`='$symbol'",
        ];

        DBHandle::delete(self::table, Mysql::makeWhereFromArray($where));
    }

    static function updatePrice($uid, $symbol, $priceType, $price)
    {
        $set             = [];
        $set[$priceType] = $price;

        $where = [
            "`uid`='$uid'",
            "`symbol`='$symbol'",
        ];

        DBHandle::update(self::table, Mysql::makeInsertString($set), Mysql::makeWhereFromArray($where));
    }

    static function getUserFav($uid)
    {
        $where = [
            "`uid`='$uid'",
        ];

        return DBHandle::select(self::table, Mysql::makeWhereFromArray($where));
    }
}
