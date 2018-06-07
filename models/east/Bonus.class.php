<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/6/7
 * Time: 下午2:26
 */
class eastBonus
{

    const table = 'east_bonus';//记录用户某个时刻的盈利状态

    static function insertRecord($uid, $bonus, $fullInfo)
    {
        $arr_columns = ['time', 'uid', 'bonus', 'fullInfo'];
        $data[]      = [
            time(),
            $uid,
            $bonus,
            json_encode($fullInfo),
        ];
        $str         = json_encode($fullInfo);

        $cacheKey = 'bonus_cache' . $uid;
        if (Mem::get($cacheKey) > time() - 60) return false;//一分钟内缓存过则不再缓存
        Mem::set($cacheKey, time());

        $cacheKey = 'bonus_md5' . $uid;
        if (Mem::get($cacheKey) == md5($str)) return false;//和上次缓存的结果一样则不缓存
        Mem::set($cacheKey, md5($str));

        DBHandle::insertMultiIgnore(self::table, $arr_columns, $data);

        return false;
    }
}
