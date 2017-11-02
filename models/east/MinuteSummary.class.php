<?php

class eastMinuteSummary
{

    const table = 'minute_summary';//每分钟各股统计

    static $columns_name = [
        'timestamp' => '时间戳',
        'symbol'    => '股票代码',
        'price'     => '时价',
        'volume'    => '成交量',
        'plus'      => '涨跌额',
        'amp'       => '涨跌幅',
    ];

    static function get($symbols = [])
    {
        $params = self::makeDownloadParams($symbols);
        $ret    = CURL::mfetch($params, 'get');

        $arr_columns = array_keys(self::$columns_name);

        $arr_data = [];
        foreach ($params as $key => $null) {
            $content = $ret[$key]['content'];
            $info    = self::getFormmatedInfo($content);

            foreach ($info as $item) {
                $temp = [];
                foreach ($arr_columns as $key) {
                    $temp[$key] = $item[$key];
                }
                $arr_data[] = $temp;
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
        $ret       = [];
        $content   = iconv("GBK", "UTF-8", $content);
        $symbol    = substr($content, 9, 8);
        $rs        = json_decode(substr($content, 18), 1);
        $yesterday = $rs['data'][$symbol]['qt'][$symbol][4];
        $date      = $rs['data'][$symbol]['data']['date'];

        $lastTotal = 0;
        foreach ($rs['data'][$symbol]['data']['data'] as $line) {
            $item = explode(' ', $line);

            $temp              = [];
            $temp['timestamp'] = strtotime("$date {$item[0]}");
            $temp['symbol']    = $symbol;
            $temp['price']     = $item[1];
            $thisTotal         = $item[2];
            $temp['volume']    = $thisTotal - $lastTotal;
            $temp['plus']      = Number::getFloat($temp['price'] - $yesterday, 2);
            $temp['amp']       = Number::getDiffRate($temp['price'], $yesterday);

            $ret[]     = $temp;
            $lastTotal = $thisTotal;
        }

        return $ret;
    }

    private static function makeDownloadParams($arr_symbols)
    {
        $params = [];

        foreach ($arr_symbols as $symbol) {
            $rand = Random::getRandomNumber();

            $params[] = [
                "url"    => "http://web.ifzq.gtimg.cn/appstock/app/minute/query?_var=min_data_$symbol&code=$symbol&r=$rand",
                "params" => [],
            ];
        }

        return $params;
    }

    static function getPrice($symbol, $t = 0)
    {
        if ($t == 0) $t = time();
        $where = "`symbol`='$symbol' AND `timestamp`<=$t ORDER BY `timestamp` DESC LIMIT 0,1";
        $rs    = DBHandle::select(self::table, $where);

        return (float)($rs[0]['price']);
    }
}
