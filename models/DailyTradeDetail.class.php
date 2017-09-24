<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/19
 * Time: 下午11:32
 */
class DailyTradeDetail
{

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
    static function getMultiDetail($arr_stock_ids, $dates)
    {
        $key     = 1;
        $keyInfo = array();
        $params  = array();
        foreach ($arr_stock_ids as $stock_id) {
            foreach ($dates as $date) {
                $params[$key]  = array(
                    "url"    => "http://market.finance.sina.com.cn/downxls.php",
                    "params" => array(
                        'date'   => $date,
                        'symbol' => $stock_id,
                    ),
                );
                $keyInfo[$key] = array('stockID' => $stock_id, 'date' => $date,);
                $key++;
            }
        }

        $ret         = CURL::mfetch($params, 'get');
        $arr_columns = array_keys(self::$arr_columns);

        foreach ($params as $key => $paraInfo) {
            $content = $ret[$key]['content'];
            $info    = self::getFormmatedInfo($content);

            $arr_data = [];
            foreach ($info as $item) {
                //顺不可随意
                $item[5]    = (strpos($item[5], '买') !== false) ? 1 : 0;
                $item[]     = $paraInfo['params']['symbol'];
                $item[]     = String::filterNoNumber($paraInfo['params']['date']);
                $item[]     = strtotime("{$paraInfo['params']['date']} {$item[0]}");
                $arr_data[] = $item;
            }
            DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
        }
    }

    private static function getFormmatedInfo($content)
    {
        $content     = iconv("GBK", "UTF-8", $content);
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
}
