<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/24
 * Time: 下午9:58
 */
class eastZhishu
{

    const url = 'http://pdfm.eastmoney.com/EM_UBG_PDTI_Fast/api/js?';

    const table = 'daily_zhishu';//每日指数

    static $names = [
        'zs0000011' => '上证指数',
        'zs3990012' => '深证成指',
    ];

    static $columns = [
        '0' => 'date',
        '1' => 'open',
        '2' => 'last',
        '3' => 'high',
        '4' => 'low',
        '5' => 'volume',
        '6' => 'amount',
        //        '7' => 'phase',//振幅
        //        '8' => 'exchange',//换手
    ];

    static $columns_name = [
        'date'      => '日期',
        'name'      => '指数名称',
        'symbol'    => '指数代码',
        'open'      => '今开',
        'last'      => '收盘价',
        'high'      => '最高',
        'low'       => '最低',
        'volume'    => '成交量',
        'amount'    => '成交额',
        'yesterday' => '昨收',
        'plus'      => '涨跌额',
        'amp'       => '涨跌幅',
    ];

    static function refresh()
    {
        $params      = self::makeDownloadParams();
        $ret         = CURL::mfetch($params, 'get');
        $arr_columns = array_keys(self::$columns_name);
        sort($arr_columns);

        $arr_data = [];
        foreach ($params as $symbol => $null) {
            $content = $ret[$symbol]['content'];
            $info    = self::getFormmatedInfo($symbol, $content);

            foreach ($info as $item) {
                $arr_data[] = $item;
            }
        }

        DBHandle::truncate(self::table);
        DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
    }

    private static function getFormmatedInfo($symbol, $content)
    {
        $ret     = [];
        $content = substr($content, 1, -1);
        $arr     = explode("\n", $content);

        foreach ($arr as $line) {
            $line = trim($line);
            if ($line == '') continue;

            $item = explode(',', $line);

            $temp = [];
            foreach (self::$columns as $i => $column) {
                $v = $item[$i];
                if (in_array($i, [0, 6, 7])) $v = String::filterNoNumber($v);
                if ($i == 5) $v = Number::getWan($v);

                $temp[$column] = $v;
            }
            $temp['symbol'] = substr($symbol, 0, -1);
            $temp['name']   = self::$names[$symbol];

            $ret[$temp['date']] = $temp;
        }

        ksort($ret);

        $last_price = 0;
        foreach ($ret as $date => $item) {
            if ($last_price == 0) {
                $ret[$date]['plus']      = 0;
                $ret[$date]['amp']       = 0;
                $ret[$date]['yesterday'] = 0;
            }
            else {
                $ret[$date]['plus']      = $item['last'] - $last_price;
                $ret[$date]['amp']       = Number::getDiffRate($item['last'], $last_price);
                $ret[$date]['yesterday'] = $last_price;
            }
            $last_price = $item['last'];
        }

        foreach ($ret as &$item) {
            ksort($item);
        }

        return $ret;
    }

    private static function makeDownloadParams()
    {
        $params = [];

        foreach (self::$names as $symbol => $null) {
            $rand            = Random::getRandomNumber();
            $params[$symbol] = [
                "url"    => self::url,
                "params" => [
                    'rtntype' => '5',
                    'id'      => String::filterNoNumber($symbol),
                    'type'    => 'k',
                    '_'       => $rand,

                ],
            ];
        }

        return $params;
    }

}
