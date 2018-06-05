<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/31
 * Time: 下午11:08
 */
class eastMoney
{

    static function getTotal()
    {
        $ret = 0;
        foreach (self::getList() as $arr) {
            foreach ($arr as $item) {
                if ($item['Status'] != 2) continue;

                $ret += (float)($item['Zzje']);
            }
        }

        return $ret;
    }

    static function getList()
    {
        $params = self::makeGetListParams();

        return self::makeGetListRequest($params);
    }

    private function makeGetListRequest($params)
    {
        $ret = [];
        $rs  = CURL::mfetch($params, 'post', eastLogin::getHeaders());

        foreach ($rs as $item) {
            $content = $item['content'];

            $arr   = json_decode($content, 1);
            $ret[] = array_reverse($arr['Data']);
        }

        return $ret;
    }

    private static function makeGetListParams()
    {
        $params = [];
        $url    = "https://jy.xzsec.com/Transfer/GetTotalTranList?validatekey=" . eastLogin::getToken();

        for ($i = 0; $i < 100; $i++) {
            $s        = -$i * 30 - 29;
            $e        = -$i * 30;
            $params[] = [
                "url"    => $url,
                "params" => [
                    'st'   => date('Y-m-d', strtotime("$s days")),
                    'et'   => date('Y-m-d', strtotime("$e days")),
                    'qqhs' => 20,
                    'dwc'  => 1,
                ],
            ];

            if (strtotime("$s days") < strtotime("2017-06-01")) break;
        }

        $params = array_reverse($params);

        return $params;
    }

}
