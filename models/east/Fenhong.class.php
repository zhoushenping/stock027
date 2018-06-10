<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/6/6
 * Time: 下午4:32
 */
class eastFenhong
{

    //数据依据页面  http://data.eastmoney.com/yjfp/
    const URL = 'http://data.eastmoney.com/DataCenter_V3/yjfp/getlist.ashx?';

    static function getLaterPrice($symbol, $price, $date)
    {
        foreach (self::getConfig($symbol) as $date_r => $item) {
            if ($date < $date_r) {
                $price = self::getFenhongPrice($price, $item['XJFH'], $item['SZZBL']);
            }
        }

        return $price;
    }

    /**
     * @param float $price 前价
     * @param float $pai   每10股派送额
     * @param float $zhuan 每10股转股数
     *
     * @return float 原来的价格转换为分红后的价格
     */
    private static function getFenhongPrice($price, $pai, $zhuan = 0.0)
    {
        return ($price - 0.1 * $pai) / (1 + 0.1 * $zhuan);
    }

    static function getConfig($symbol)
    {
        static $info = [];

        if (empty($info)) {
            $info = self::queryFromOnline();
        }

        $ret = $info[$symbol];
        ksort($ret);

        return $ret;
    }

    static function queryFromOnline()
    {
        static $ret = [];

        if ($ret) return $ret;//使用php静态变量加速

        $cacheKey = 'fenhong';
        $ret      = Mem::get($cacheKey);

        if (!$ret) {
            $params = self::makeParams();
            $rs     = CURL::mfetch($params, 'get', eastLogin::getHeaders());
            $ret    = self::getFormattedInfo($rs);
            Mem::set($cacheKey, $ret, 86400);
        }

        return $ret;
    }

    private static function getFormattedInfo($rs)
    {
        $ret = [];
        krsort($rs);
        foreach ($rs as $item) {
            $content = $item['content'];
            $content = iconv("GBK", "UTF-8", $content);
            $record  = json_decode($content, 1);
            $record  = $record['data'];

            foreach ($record as $iitt) {
                if ($iitt['CQCXR'] == '-') continue;
                $iitt['symbol']              = StockList::getStandardSymbol($iitt['Code']);
                $date                        = date('Ymd', strtotime($iitt['CQCXR']));
                $ret[$iitt['symbol']][$date] = $iitt;
            }
        }

        return $ret;
    }

    private static function makeParams()
    {
        $params = [];
        $url    = self::URL;

        $filters = [
//            '(ReportingPeriod=^2016-06-30^)',
//            '(ReportingPeriod=^2016-12-31^)',
//            '(ReportingPeriod=^2017-06-30^)',
'(ReportingPeriod=^2017-12-31^)',
        ];

        foreach ($filters as $filter) {
            for ($page = 1; $page <= 20; $page++) {
                $params[] = array(
                    "url"    => $url,
                    "params" => array(
                        'js'       => 'var duBZREVp',
                        'pagesize' => 300,
                        'page'     => $page,
                        'sr'       => -1,
                        'sortType' => 'YAGGR',
                        'filter'   => $filter,
                        'rt'       => Random::getRandom(8),
                    ),
                );
            }
        }

        return $params;
    }
}
