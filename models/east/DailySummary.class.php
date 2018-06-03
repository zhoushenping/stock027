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
        $params = self::makeDownloadParams($symbols);
        $ret    = CURL::mfetch($params, 'get');

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

    public static function getExistSymbols($date)
    {
        $ret = [];
        $rs  = DBHandle::select(self::table, "`date`='$date'", "`symbol`");
        foreach ($rs as $item) {
            $ret[] = $item['symbol'];
        }

        return $ret;
    }

    public static function getAverage($symbol, $days = 5)
    {
        $ret   = [];
        $dates = TradeDate::getTradeDates($days);
        foreach ($dates as $date) {
            $date = String::filterNoNumber($date);
            foreach (DailyTradeDetail::getDailyDetail($symbol, $date) as $item) {
                $ret['volumes'] += $item['volume'];
                $ret['amounts'] += $item['amount'];
            }
        }

        return Number::getFloat($ret['amounts'] / $ret['volumes'], 3);
    }

    public static function getLastAverageForAll($days = 5)
    {
        $info       = [];
        $ret        = [];
        $dates      = [];
        $dates_temp = TradeDate::getTradeDates($days);
        foreach ($dates_temp as $date) {
            $date    = String::filterNoNumber($date);
            $dates[] = $date;
        }

        $str_dates = implode(',', $dates);
        $rs        = DBHandle::select(self::table, "`date` IN ($str_dates)");

        foreach ($rs as $item) {
            $info[$item['symbol']][$item['date']] = [
                'volume' => $item['volume'],
                'amount' => $item['amount'],
            ];
        }

        foreach (StockList::getSymbols() as $symbol) {
            foreach ($dates as $date) {
                if (!isset($info[$symbol][$date]['volume'])) {
                    foreach (DailyTradeDetail::getDailyDetail($symbol, $date) as $item) {
                        $info[$symbol][$date]['volume'] += $item['volume'];
                        $info[$symbol][$date]['amount'] += $item['amount'];
                    }
                }
            }
        }

        foreach ($info as $symbol => $dailyData) {
            $a = [];

            foreach ($dailyData as $item) {
                $a['volumes'] += $item['volume'];
                $a['amounts'] += $item['amount'];
            }
            $ret[$symbol] = Number::getFloat($a['amounts'] / $a['volumes'], 3);
        }

        return $ret;
    }

    static function getDayLastPriceList($date)
    {
        $date = String::filterNoNumber($date);
        $ret  = [];
        $rs   = DBHandle::select(self::table, "`date`='$date'");
        foreach ($rs as $item) {
            $ret[$item['symbol']] = $item['last'];
        }

        return $ret;
    }

    static function getTopList()
    {
        $date = date('Ymd', strtotime('-30 days'));

        return DBHandle::select(self::table, "`date`>=$date and `amp`>=9.9");
    }
}
