<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/24
 * Time: 下午9:58
 */

class eastDailySummary
{

    const table = 'daily_summary';//每日各股统计

    static $columns = [
        '1'  => 'name',
        '5'  => 'open',
        '4'  => 'yesterday',
        '41' => 'high',
        '42' => 'low',
        '37' => 'amount',
        '36' => 'volume',
        '31' => 'plus',
        '32' => 'amp',
        '3'  => 'last',
    ];

    static $columns_name = [
        'date'      => '日期',
        'name'      => '股票名称',
        'symbol'    => '股票代码',
        'open'      => '今开',
        'yesterday' => '昨收',
        'high'      => '最高',
        'low'       => '最低',
        'last'      => '收盘价',
        'amount'    => '成交额',
        'volume'    => '成交量',
        'plus'      => '涨跌额',
        'amp'       => '涨跌幅',
    ];

    static function get($symbols = [])
    {
        $params      = self::makeDownloadParams($symbols);
        $ret         = CURL::mfetch($params, 'get');

        $arr_columns = array_keys(self::$columns_name);
        sort($arr_columns);

        $arr_data = [];
        foreach ($params as $key => $null) {
            $content = $ret[$key]['content'];
            $info    = self::getFormmatedInfo($content);

            foreach ($info as $item) {
                $arr_data[] = $item;
            }
        }

        DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
    }

    static function getAll()
    {
        $symbols = StockList::getSymbols(0);
        self::get($symbols);
    }

    private static function getFormmatedInfo($content)
    {
        $ret     = [];
        $content = iconv("GBK", "UTF-8", $content);
        $arr     = explode(';', $content);

        foreach ($arr as $line) {
            $line = trim($line);
            if ($line == '') continue;
            $symbol = substr($line, 2, 8);
            $line   = substr($line, strpos($line, '=') + 2, -1);

            $item = explode('~', $line);

            $temp = [];
            foreach (self::$columns as $i => $column) {
                $temp[$column]  = $item[$i];
                $temp['date']   = date('Ymd');
                $temp['symbol'] = $symbol;
            }

            ksort($temp);

            $ret[] = $temp;
        }

        return $ret;
    }

    private static function makeDownloadParams($arr_symbols)
    {
        $each   = 80;
        $params = [];

        foreach (array_chunk($arr_symbols, $each) as $subArr) {
            $rand     = Random::getRandomNumber();
            $str_list = implode(',', $subArr);

            $params[] = [
                "url"    => "http://qt.gtimg.cn/q=$str_list&r=$rand",
                "params" => [],
            ];
        }

        return $params;
    }
}
