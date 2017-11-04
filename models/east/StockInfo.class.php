<?php

class eastStockInfo
{

    const table = 'east_stock_info';//各股简要信息

    static $columns = [
        '0'  => 'stockType',//股票类型  A-A股 B-B股  I-指数
        '1'  => 'pinyin',
        '2'  => 'lastyear_mgsy',//前一年每股收益和
        '3'  => 'fourQ_mgsy',//最近四个季度每股收益和
        '4'  => 'column4',
        '5'  => 'mgjzc',//最近报告的每股净资产
        '6'  => 'column6',
        '7'  => 'totalcapital',//总股本
        '8'  => 'currcapital',//流通股本
        '9'  => 'lta',//流通A股,老数据保留
        '10' => 'column10',
        '11' => 'currency',
        '12' => 'profit',//最近年度净利润
        '13' => 'profit_four',//最近四个季度净利润
        '14' => 'column14',
        '15' => 'column15',
        '16' => 'column16',
        '17' => 'column17',
        '18' => 'column18',
    ];

    static $columns_name = [
    ];

    static function get($symbols = [])
    {
        $params = self::makeDownloadParams($symbols);
        $ret    = CURL::mfetch($params, 'post');

        $arr_columns   = self::$columns;
        $arr_columns[] = 'symbol';

        $arr_data = [];
        foreach ($params as $key => $null) {
            $content = $ret[$key]['content'];
            $info    = self::getFormmatedInfo($content);

            foreach ($info as $item) {
                $temp = [];
                foreach ($arr_columns as $k) {
                    $temp[$k] = $item[$k];
                }

                $arr_data[] = $item;
            }
        }
        DBHandle::execute("DELETE FROM `" . self::table . "`");
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

        $arr = explode(';', $content);

        foreach ($arr as $line) {
            $line = trim($line);
            if ($line == '') continue;
            $symbol = substr($line, 11, 8);
            $line   = substr($line, strpos($line, '=') + 2, -1);

            $item = explode(',', $line);

            $temp = [];
            foreach (self::$columns as $i => $column) {
                $temp[$column] = $item[$i];
            }
            $temp['symbol'] = $symbol;

            $ret[] = $temp;
        }

        return $ret;
    }

    private static function makeDownloadParams($arr_symbols)
    {
        $each   = 300;
        $params = [];

        foreach (array_chunk($arr_symbols, $each) as $subArr) {
            $temp = [];
            foreach ($subArr as $symbol) {
                $temp[] = "{$symbol}_i";
            }

            $rand     = Random::getRandomNumber();
            $str_list = implode(',', $temp);

            $params[] = [
                "url"    => "http://hq.sinajs.cn/rn=$rand&list=$str_list",
                "params" => [],
            ];
        }

        return $params;
    }

    static function readRecord($symbol)
    {
        $symbol = addslashes($symbol);
        $rs     = DBHandle::select(self::table, "`symbol`='$symbol'");

        return $rs[0];
    }
}

