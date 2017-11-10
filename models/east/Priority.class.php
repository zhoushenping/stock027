<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/11/10
 * Time: 下午12:08
 */
class eastPriority
{

    const table = 'east_priority';

    static function set($symbol, $num)
    {
        $record = self::get();
        $num    = (int)$num;
        $symbol = addslashes($symbol);

        if (isset($record[$symbol])) {
            DBHandle::update(self::table, "`priority`=$num", "`symbol`='$symbol'");
        }
        else {
            DBHandle::insertMultiIgnore(self::table, ['symbol', 'priority'], [[$symbol, $num]]);
        }
    }

    static function get()
    {
        foreach (DBHandle::select(self::table) as $item) {
            $ret[$item['symbol']] = $item['priority'];
        }

        return $ret;
    }
}
