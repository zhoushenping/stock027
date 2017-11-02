<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/31
 * Time: 下午9:42
 */
class eastRevoke
{

    static $columns_name = [
        'Wtrq' => '委托日期',
        'Wtbh' => '委托编号',
        'Wtsj' => '委托时间',
        'Zqdm' => '证券代码',
        'Zqmc' => '证券名称',
        'Mmsm' => '委托方向',
        'Wtsl' => '委托数量',
        'Wtzt' => '委托状态',
        'Wtjg' => '委托价格',
        'Cjsl' => '成交数量',
        'Cjje' => '成交金额',
        'Cjjg' => '成交价格',
        'Cjje' => '成交金额',
    ];

    static function getList()
    {
        $params = self::makeGetListParams();

        return self::makeGetListRequest($params);
    }

    private function makeGetListRequest($params)
    {
        $ret = [];
        $rs  = CURL::mfetch($params, 'get', eastLogin::getHeaders());

        foreach ($rs as $item) {
            $content = $item['content'];
            $rs      = json_decode($content, 1);
            $ret     = $rs['Data'];
        }

        return $ret;
    }

    private static function makeGetListParams()
    {
        $params = [];
        $url    = "https://jy.xzsec.com/Trade/GetRevokeList?validatekey=" . eastLogin::getToken();

        $params[] = [
            "url"    => $url,
            "params" => [],
        ];

        return $params;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    static function revoke($revokes = [])
    {
        $params = self::makeRevokeParams($revokes);

        self::makeRovokeRequest($params);
    }

    private function makeRovokeRequest($params)
    {
        CURL::mfetch($params, 'post', eastLogin::getHeaders());
    }

    //$revokes=['Wtrq1_Wtbh1','Wtrq2_Wtbh2',]
    private static function makeRevokeParams($revokes = [])
    {
        $params = [];
        $url    = "https://jy.xzsec.com/Trade/RevokeOrders?validatekey=" . eastLogin::getToken();

        $params[] = [
            "url"    => $url,
            "params" => [
                'revokes' => implode(',', $revokes),
            ],
        ];

        return $params;
    }
}
