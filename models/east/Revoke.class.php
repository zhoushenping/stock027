<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/31
 * Time: 下午9:42
 */
class eastRevoke
{

    const table = 'east_revoke_list';
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

        $data = self::makeGetListRequest($params);
        if (!empty($data)) {
            $arr_columns = array_keys($data[0]);

            foreach ($data as $k => $item) {
                $data[$k]['Zqdm'] = StockList::getStandardSymbol($data[$k]['Zqdm']);
            }

            DBHandle::delete(self::table);
            DBHandle::insertMultiIgnore(self::table, $arr_columns, $data);

            self::updateRecordInfo();//用自定义的方法更新记录中的部分字段
            return self::readRecord();
        }
        else {
            return [];
        }
    }

    static function readRecord()
    {
        return DBHandle::select(self::table, "1 ORDER BY `price_diff_rate`");
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

    static function updateRecordInfo()
    {
        foreach (self::readRecord() as $item) {
            $priceNow = eastSalary::getPrice($item['Zqdm'], time() + 86400);
            $diffRate = Number::getDiffRate($priceNow, $item['Wtjg']);
            DBHandle::update(self::table, "`price_diff_rate`='$diffRate'", "`id`={$item['id']}");
        }
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
