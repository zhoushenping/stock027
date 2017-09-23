<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/20
 * Time: 下午11:40
 */
class DailySummary
{

    const table = 'daily_summary';//todo 表的字段的存储类型待整理优化

    static $online = true;

    //各种类型分别有多少页
    //5为富余量
    static $pageInfo = array(
        'sh_a' => 16 + 5,//沪市A股
        'sz_a' => 26 + 5,//深市A股
        //创业板等板块暂不收录
    );

    //顺不可随意
    static $arr_columns = [
        'symbol'        => '代码',
        'code'          => '代码2',
        'name'          => '名称',
        'trade'         => '最新价',
        'pricechange'   => '涨跌额',
        'changepercent' => '涨跌幅',
        'buy'           => '买入',//
        'sell'          => '卖出',
        'settlement'    => '昨收',
        'open'          => '今开',
        'high'          => '最高',
        'low'           => '最低',
        'volume'        => '成交量/手',
        'amount'        => '成交额/元',
        'ticktime'      => '',
        'per'           => '市盈率',//?
        'pb'            => '市净率',//?
        'mktcap'        => '总市值/万',
        'nmc'           => '流通市值/万',
        'turnoverratio' => '换手率',
        'date'          => '日期',
        'amp'           => '振幅',
    ];

    static function getAll()
    {
        $params = self::makeDownloadParams();
        $ret    = CURL::mfetch($params);

        $arr_columns = array_keys(self::$arr_columns);
        $date        = TradeDate::getTradeDates()[0];

        foreach ($params as $key => $null) {
            $content = $ret[$key]['content'];
            $info    = self::getFormmatedInfo($content);

            if ($info == false) continue;

            $arr_data = [];
            foreach ($info as $item) {
                //顺不可随意
                $item['date'] = $date;
                $item['apm']  = self::getAmplitude($item);
                $arr_data[]   = $item;
            }
            DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
        }
    }

    //计算振幅
    static function getAmplitude($item)
    {
        //振幅=(最高价-最低价)/前日收盘价*100%
        return Number::getPercent(abs($item['high'] - $item['low']), $item['settlement'], 4);
    }

    private static function makeDownloadParams()
    {
        $params = [];
        foreach (self::$pageInfo as $node => $pageCount) {
            if (self::$online == false) $pageCount = 1;

            for ($i = 1; $i <= $pageCount; $i++) {
                $params[] = array(
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
            }
        }

        return $params;
    }

    private static function getFormmatedInfo($content)
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
}
