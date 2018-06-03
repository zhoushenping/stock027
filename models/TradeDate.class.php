<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/23
 * Time: 下午6:23
 * 关于交易日的逻辑代码请尽量放在这个类里面
 */
class TradeDate
{

    const table = 'trade_dates';

    //未来的 额外不交易日期
    static $plus_holidays = [
        20180618,
        20180924,
        20181001,
        20181002,
        20181003,
        20181004,
        20181005,
    ];

    //获取最近n天内的的交易日
    static function getTradeDates($count = 365)
    {
        return array_slice(self::getTradeDatesFromDB(), 0, $count);
    }

    static function isTradeDate($date)
    {
        $rs = self::getTradeDatesFromDB();

        return in_array($date, $rs);
    }

    static function renewTradeDatesFromApi()
    {
        $params   = self::makeDownloadParams();
        $rs       = CURL::mfetch($params, 'get');
        $arr_data = [];

        foreach ($params as $key => $null) {
            $content = $rs[$key]['content'];
            $info    = self::getFormmatedInfo($content);
            foreach ($info as $val) {
                $arr_data[] = [$val];
            }
        }

        DBHandle::insertMultiIgnore(self::table, ['date'], $arr_data);
    }

    private static function getFormmatedInfo($content)
    {
        $ret     = [];
        $content = iconv("GBK", "UTF-8", $content);
        $content = str_replace(':"', '":"', $content);
        $content = str_replace('",', '","', $content);
        $content = str_replace('{', '{"', $content);

        $arr = json_decode($content, 1);
        foreach ($arr as $item) {
            if ($item['trade'] > 0) {
                $ret[] = date('Ymd', strtotime($item['opendate']));
            }
        }

        return $ret;
    }

    private static function makeDownloadParams()
    {
        $params  = [];
        $count   = self::getTradeDatesFromDB() == [] ? 20000 : 20;
        $symbols = StockList::getSymbols();
        shuffle($symbols);
        $symbols   = array_slice($symbols, 0, 10);
        $symbols[] = 'sz000001';
        $symbols[] = 'sh600000';

        foreach ($symbols as $symbol) {
            $params[] = [
                "url"    => "http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/MoneyFlow.ssl_qsfx_lscjfb",
                "params" => [
                    'page'  => 1,
                    'num'   => $count,
                    'sort'  => 'opendate',
                    'asc'   => 0,
                    'daima' => $symbol,
                ],
            ];
        }

        return $params;
    }

    static function getTradeDatesFromDB()
    {
        $ret = [];
        foreach (DBHandle::select(self::table, "1 ORDER BY `date` DESC") as $val) {
            $ret[] = $val['date'];
        }

        return $ret;
    }
}
