<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/11/11
 * Time: 下午3:12
 */
class eastBigSale
{

    static $sampleThreshold = [
        'amp'         => -2,
        'syl'         => 50,
        'marketValue' => 400,
    ];

    static function findLow($threshold = [])
    {
        $ret = [];
        if (empty($threshold)) {
            $threshold = self::$sampleThreshold;
        }

        foreach (eastLast::getAllRecords() as $last) {
            if ($last['open'] == 0) continue;
            if ($threshold['amp'] <= 0 && $last['amp'] > $threshold['amp']) continue;
            if ($threshold['amp'] > 0 && $last['amp'] < $threshold['amp']) continue;
            if ($last['marketValue'] < $threshold['marketValue']) continue;
            if ($last['syl'] > $threshold['syl']) continue;

            $last['syl']         = Number::getFloat($last['syl'], 0);
            $last['marketValue'] = Number::getFloat($last['marketValue'], 0);

            $ret[] = $last;
        }

        return $ret;
    }

    static function readAll()
    {
        return DBHandle::select(eastLast::table);
    }
}
