<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/19
 * Time: 下午11:32
 */
class DailyTradeDetail
{

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

        $ret = CURL::mfetch($params, 'get');

//        var_dump($ret);

        foreach ($params as $key => $info) {
            $content  = $ret[$key]['content'];
            $content  = iconv("GBK", "UTF-8", $content);
            $fileName = "{$info['params']['symbol']}_{$info['params']['date']}.xls";
            self::saveDownload($content, $fileName);
        }
    }

    static function saveDownload($content, $fileName)
    {
        error_log($content, 3, "./download/$fileName");
    }
}
