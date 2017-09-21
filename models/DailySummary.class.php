<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/20
 * Time: 下午11:40
 */
class DailySummary
{

    const table = 'daily_summary';

    static $pageInfo = array(
        'sh_a' => 16 + 5,//5为富余量
        'sz_a' => 26 + 5,
    );

    static $pageInfo_test = array(
        'sh_a' => 1,//5为富余量
        'sz_a' => 1,
    );

    //http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=1&num=80&sort=symbol&asc=1&node=sh_a&symbol=&_s_r_a=page
    //http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=1&num=80&sort=symbol&asc=1&node=sz_a&symbol=&_s_r_a=page

    static function getAll()
    {
        $key     = 1;
        $keyInfo = array();
        $params  = array();
        foreach (self::$pageInfo as $node => $pageCount) {
            for ($i = 1; $i <= $pageCount; $i++) {
                $params[$key]  = array(
                    "url"    => "http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData",
                    "params" => array(
                        'page'   => $i,
                        'num'    => 80,
                        'sort'   => 'symbol',
                        'asc'    => '1',
                        'node'   => $node,
                        'symbol' => '',
                        '_s_r_a' => 'page',
                    ),
                );
                $keyInfo[$key] = array('node' => $node, 'page' => $i,);
                $key++;
            }
        }

        $ret = CURL::mfetch($params, 'get');

        $arr_columns = self::getColumns();

        foreach ($params as $key => $null) {
            $content = $ret[$key]['content'];
            $info    = self::getFormmatedInfo($content);

            if ($info == false) continue;

            $arr_data = [];
            foreach ($info as $item) {
                $item['date'] = date('Ymd');
                $arr_data[]   = $item;
            }
            DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
        }
    }

    static function getFormmatedInfo($content)
    {
        $content = iconv("GBK", "UTF-8", $content);
        //替换15:00:00这样的字符为空 一面干扰后面的纠正json格式   todo  以后优化
        $content = preg_replace("/\\d{2}:\\d{2}:\\d{2}/", "", $content);
        $content = str_replace('{', '{"', $content);
        $content = str_replace(',', ',"', $content);
        $content = str_replace(':', '":', $content);
        $content = str_replace('},"{', '},{', $content);

        $info = json_decode($content, 1);

        return $info;
    }

    static function getColumns()
    {
        return $arr_columns = [
            'symbol',
            'code',
            'name',
            'trade',
            'pricechange',
            'changepercent',
            'buy',
            'sell',
            'settlement',
            'open',
            'high',
            'low',
            'volume',
            'amount',
            'ticktime',
            'per',
            'pb',
            'mktcap',
            'nmc',
            'turnoverratio',
            'date',
        ];
    }
}
