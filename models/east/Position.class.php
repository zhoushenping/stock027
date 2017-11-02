<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/31
 * Time: 下午11:08
 */
class eastPosition
{

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

            $rs  = json_decode($content, 1);
            $ret = $rs['Data'];
        }

        return $ret;
    }

    private static function makeGetListParams()
    {
        $params = [];
        $url    = "https://jy.xzsec.com/Com/GetAssetsEx?validatekey=" . eastLogin::getToken();

        $params[] = [
            "url"    => $url,
            "params" => [
                'moneyType' => 'RMB',
            ],
        ];

        return $params;
    }
}
