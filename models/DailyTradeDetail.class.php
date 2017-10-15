<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/19
 * Time: 下午11:32
 */
class DailyTradeDetail
{

    //QQ股票详单下载地址样本 http://stock.gtimg.cn/data/index.php?appn=detail&action=download&c=sz002354&d=20170925

    const table = 'trade_detail';//交易详单

    //顺序不可随意
    static $arr_columns = [
        'time'        => '时间',
        'price'       => '成交价',
        'pricechange' => '价格变动',
        'volume'      => '成交量/手',
        'amount'      => '成交额/元',
        'type'        => '性质',
        'symbol'      => '代码',
        'date'        => '日期',
        'timestamp'   => '时间戳',
    ];

    //样本 getMultiDetail(['sz002354'], ['2017-09-22',]);
    static function downloadMulti($arr_stock_ids, $dates)
    {
        $key     = 1;
        $keyInfo = array();
        $params  = array();
        foreach ($arr_stock_ids as $stock_id) {
            foreach ($dates as $date) {
                $params[$key]  = array(
                    "url"    => "http://stock.gtimg.cn/data/index.php",
                    "params" => array(
                        'appn'   => 'detail',
                        'action' => 'download',
                        'd'      => String::filterNoNumber($date),
                        'c'      => $stock_id,
                    ),
                );
                $keyInfo[$key] = array('stockID' => $stock_id, 'date' => $date,);
                $key++;
            }
        }

        $ret = CURL::mfetch($params, 'get');

        foreach ($params as $key => $paraInfo) {
            $symbol = $paraInfo['params']['c'];
            $date   = $paraInfo['params']['d'];

            $content = $ret[$key]['content'];
            $content = iconv("GBK", "UTF-8", $content);
            if (!is_dir(ROOT_PATH . "/download/$date")) {
                mkdir(ROOT_PATH . "/download/$date", 0777);
            }
            error_log($content, 3, ROOT_PATH . "/download/$date/{$symbol}.xls");
        }
    }

    private static function getFormmatedInfo($content)
    {
        if (strpos($content, '成交') === false) return false;

        $arr_content = explode("\n", $content);

        $ret = [];
        foreach ($arr_content as $line) {
            $line = trim($line);
            if ($line == '') continue;
            if (strpos($line, '成交') !== false) continue;
            $ret[] = explode("\t", $line);
        }

        return $ret;
    }

    //样本 getDailyDetail('sh600000', 20171012);
    static function getDailyDetail($symbol, $date)
    {
        $content = file_get_contents(ROOT_PATH . "/download/$date/{$symbol}.xls");
        $info    = self::getFormmatedInfo($content);

        if (empty($info)) {
            return [];
        }

        $arr_columns = array_keys(self::$arr_columns);
        $ret         = [];
        foreach ($info as $item) {
            //顺不可随意
            $item[5] = (strpos($item[5], '买') !== false) ? 1 : 0;
            $item[]  = $symbol;
            $item[]  = $date;
            $item[]  = strtotime("{$date} {$item[0]}");

            $temp = [];
            foreach ($item as $k => $v) {
                $temp[$arr_columns[$k]] = $v;
            }
            $ret[] = $temp;
        }

        return $ret;
    }
}
