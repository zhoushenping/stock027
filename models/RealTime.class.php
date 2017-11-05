<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/23
 * Time: 下午9:55
 */
class RealTime
{

    const table = 'real_time';//实时查询

    //顺序不可随意
    static $arr_columns = [
        'name'         => '名称',//0
        'open'         => '今开',//1
        'settlement'   => '昨收',//2
        'trade'        => '最新价',//3
        'high'         => '最高',//4
        'low'          => '最低',//5
        'current_buy'  => '竞买价',//6
        'current_sell' => '竞卖价',//7
        'volume'       => '成交量/手',//8
        'amount'       => '成交额/元',//9
        'b1c'          => '买1量',//10
        'b1p'          => '买1价',//11
        'b2c'          => '买2量',//12
        'b2p'          => '买2价',//13
        'b3c'          => '买3量',//14
        'b3p'          => '买3价',//15
        'b4c'          => '买4量',//16
        'b4p'          => '买4价',//17
        'b5c'          => '买5量',//18
        'b5p'          => '买5价',//19
        's1c'          => '卖1量',//20
        's1p'          => '卖1价',//21
        's2c'          => '卖2量',//22
        's2p'          => '卖2价',//23
        's3c'          => '卖3量',//24
        's3p'          => '卖3价',//25
        's4c'          => '卖4量',//26
        's4p'          => '卖4价',//27
        's5c'          => '卖5量',//28
        's5p'          => '卖5价',//29
        'date'         => '日期',//30
        'time'         => '时间',//31
        'unkown'       => '未知',//32
        'symbol'       => '代码',//33 人为加
        'timestamp'    => '时间戳',//34 人为加
    ];

    static function get($symbols = [], $act = 'return')
    {
        $params      = self::makeDownloadParams($symbols);
        $ret         = CURL::mfetch($params, 'post');//post不要轻易改变为get 切记
        $arr_columns = array_keys(self::$arr_columns);

        $arr_data = [];
        foreach ($params as $key => $null) {
            $content = $ret[$key]['content'];
            $info    = self::getFormmatedInfo($content);

            foreach ($info as $item) {
                //顺序不可随意
                if (count($item) != count($arr_columns)) continue;
                $arr_data[] = self::mergeItem($arr_columns, $item);
            }
        }
        if ($act == 'return') {
            return $arr_data;
        }
        else {
            DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);

            return [];
        }
    }

    static function mergeItem($keys, $item)
    {
        $ret = [];
        foreach ($item as $k => $v) {
            $ret[$keys[$k]] = $v;
        }

        return $ret;
    }

    static function getAll()
    {
        $symbols = StockList::getSymbols(0);
        self::get($symbols, 'writeDB');
    }

    private static function getFormmatedInfo($content)
    {
        $ret     = [];
        $content = iconv("GBK", "UTF-8", $content);

        $arr = explode(';', $content);

        foreach ($arr as $line) {
            $line = trim($line);
            if ($line == '') continue;
            $symbol = substr($line, strpos($line, 'hq_str') + 7, 8);
            $line   = substr($line, strpos($line, '=') + 2, -1);

            $item      = explode(',', $line);
            $timestamp = strtotime("{$item[30]} {$item[31]}");

            $item[30] = String::filterNoNumber($item[30]);//去掉日期中的-
            $item[]   = $symbol;
            $item[]   = $timestamp;
            $ret[]    = $item;
        }

        return $ret;
    }

    private static function makeDownloadParams($arr_symbols)
    {
        $each   = 800;//经测试 每次查的值为900时  新浪返回空值      800可用
        $params = [];

        foreach (array_chunk($arr_symbols, $each) as $subArr) {
            $rand     = Random::getRandomNumber();
            $str_list = implode(',', $subArr);

            //这个链接的参数拼接有特殊要求,改动后请试验无误后方可上线
            $params[] = [
                "url"    => "http://hq.sinajs.cn/etag.php?_=$rand&list=$str_list",
                "params" => [],
            ];
        }

        return $params;
    }

    static function getPrice($symbol, $t = 0)
    {
        if ($t == 0) $t = time();
        $where = "`symbol`='$symbol' AND `timestamp`<=$t AND trade>0 ORDER BY `timestamp` DESC LIMIT 0,1";
        $rs    = DBHandle::select(self::table, $where);

        return (float)($rs[0]['trade']);
    }

    static function getCurrentSellPrice($symbol, $t = 0)
    {
        if ($t == 0) $t = time();
        $where = "`symbol`='$symbol' AND `timestamp`<=$t AND trade>0 ORDER BY `timestamp` DESC LIMIT 0,1";
        $rs    = DBHandle::select(self::table, $where);

        return (float)($rs[0]['current_sell']);
    }
}
