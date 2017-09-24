<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/25
 * Time: 上午12:52
 */
class MinuteSummary
{

    const table = 'minute_summary';//分钟小计

    //顺序不可随意
    static $arr_columns = [
        'symbol'      => '代码',
        'date'        => '日期',
        'minute'      => '分钟',
        'price'       => '价格',
        'price_av'    => '均价',
        'pricechange' => '价格变动',
        'volume'      => '成交量/手',
    ];

    //样本 calculate('sz002354', 20170922);
    //todo 计算结果和新浪的有部分差异  待修复
    static function calculate($symbol, $date)
    {
        $ret = [];
        $rs  = DailyTradeDetail::getDailyDetail($symbol, $date);
        $rs  = array_reverse($rs);

        $current_minute = '';
        $current_volume = 0;
        $current_amount = 0;

        foreach ($rs as $item) {
            $minute = substr($item['time'], 0, -3);

            if ($current_minute != '' && $minute != $current_minute) {
                $ret[$current_minute]['price_av'] = self::getAverage($current_amount, $current_volume);
            }

            $ret[$minute]['volume'] += $item['volume'];
            $ret[$minute]['price'] = $item['price'];

            $current_minute = $minute;
            $current_volume += $item['volume'];
            $current_amount += $item['amount'];
        }
        $ret[$current_minute]['price_av'] = self::getAverage($current_amount, $current_volume);

        //相对昨收的涨跌量、涨跌幅   日期时间输出  todo

        return $ret;
    }

    static function getAverage($amount, $volume)
    {
        return number_format(($amount / ($volume * 100)), 2, '.', '');
    }
}
