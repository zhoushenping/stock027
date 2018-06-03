<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/23
 * Time: 下午9:55
 */
class eastLast
{

    const table = 'east_last';//最近的状态

    static function getAllRecords()
    {
        static $rs = [];
        if (empty($rs)) {
            $rs = DBHandle::select(self::table);
        }

        return $rs;
    }

    static function renewRecords($arr_columns, $data)
    {
        $arr_data      = [];
        $arr_columns[] = 'syl';
        $arr_columns[] = 'marketValue';
        $arr_columns[] = 'amp';

        DBHandle::truncate(self::table);

        $allStockInfo = eastStockInfo::readAllRecord();

        foreach ($data as $item) {
            $stockInfo           = $allStockInfo[$item['symbol']];
            $price               = $item['trade'];
            $item['syl']         = Number::getFloat($price / $stockInfo['fourQ_mgsy'], 2);//市盈率
            $item['marketValue'] = Number::getFloat($price * $stockInfo['totalcapital'] / 10000, 2);//市值  单位为亿
            $item['amp']         = Number::getDiffRate($item['trade'], $item['settlement']);
            $arr_data[]          = $item;
        }

        DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
    }

    static function getLastPriceList()
    {
        $ret = [];
        $rs  = DBHandle::select(self::table);
        foreach ($rs as $item) {
            $ret[$item['symbol']] = $item['trade'];
        }

        return $ret;
    }

    static function getLastInfo()
    {
        $ret = [];
        $rs  = DBHandle::select(self::table);
        foreach ($rs as $item) {
            $ret[$item['symbol']] = $item;
        }

        return $ret;
    }

}
