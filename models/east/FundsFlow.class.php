<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/31
 * Time: 下午9:42
 */
class eastFundsFlow
{

    static $beginDate = [
        '1001' => '2007-09-01',
        '1002' => '2017-09-01',
    ];

    static $columns_name = [
        'Wtrq' => '委托日期',
    ];

    static function getList()
    {
        $params = self::makeDownloadParams();

        return self::makeGetListRequest($params);
    }

    private function makeGetListRequest($params)
    {
        $ret = [];
        $rs  = CURL::mfetch($params, 'post', eastLogin::getHeaders());

        foreach ($rs as $item) {
            $content = $item['content'];
            $rs      = json_decode($content, 1);
            $ret     = array_merge($ret, $rs['Data']);
        }

        return $ret;
    }

    private static function makeDownloadParams()
    {
        $params = [];
        $url    = "https://jy.xzsec.com/Search/GetFundsFlow?validatekey=" . eastLogin::getToken();

        foreach (self::getQueryDates() as $month => $monthlyDate) {
            $params[$month] = array(
                "url"    => $url,
                "params" => array(
                    'st'   => $monthlyDate[0],
                    'et'   => $monthlyDate[1],
                    'qqhs' => 200000,
                    'dwc'  => '',
                ),
            );
        }

        return $params;
    }

    //获取要查询的日期（每月首尾日期）
    static function getQueryDates()
    {
        $ret   = [];
        $dates = Calendar::getMonthlyDates();
        $dates = array_reverse($dates);

        foreach ($dates as $month => $monthlyDate) {
            $d0   = $monthlyDate[0];
            $dl   = $monthlyDate[count($monthlyDate) - 1];
            $d_my = self::$beginDate[eastLogin::$uid];
            if (Calendar::isDateBefore($dl, $d_my)) continue;

            $ret[$month] = [$d0, $dl];
        }

        return $ret;
    }
}
