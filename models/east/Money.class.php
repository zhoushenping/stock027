<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/31
 * Time: 下午11:08
 */
class eastMoney
{

    const table = 'east_money';//银证转账信息

    static $columns_name = [
        'Zzrq'   => '转账日期',//20180518
        'Zzsj'   => '转账时间',//092100
        'Yhdm'   => '银行代码',//2021
        'Yhmc'   => '银行名称',//交行存管
        'Zzje'   => '资金金额',//30000.00
        'Ywmc'   => '业务名称',//银行转证券
        'Fshye'  => '最低',//61222.22
        'Qrxx'   => '确认信息',//交易成功
        'Wtbh'   => '',//委托编号
        'Zjzh'   => '资金账号',//541200145700
        'Status' => '状态代码',//2
    ];

    static function getTotal()
    {
        $ret = 0;
        foreach (self::readAllRecord() as $item) {
            $ret += $item['Zzje'];
        }

        return $ret;
    }

    static function readAllRecord()
    {
        return DBHandle::select(self::table, "1 ORDER BY `Zzrq` DESC");
    }

    static function getLatestDate()
    {
        $rs = self::readAllRecord();

        return $rs[0]['Zzrq'];
    }

    static function refresh()
    {
        $params = self::makeDownloadParams();
        $rs     = CURL::mfetch($params, 'post', eastLogin::getHeaders());

        $arr_columns = array_keys(self::$columns_name);
        sort($arr_columns);

        $arr_data = [];
        foreach ($rs as $result) {
            $arr  = json_decode($result['content'], 1);
            $info = array_reverse($arr['Data']);

            foreach ($info as $item) {
                ksort($item);
                $arr_data[] = $item;
            }
        }

        DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
    }

    private static function makeDownloadParams()
    {
        $params = [];
        $url    = "https://jy.xzsec.com/Transfer/GetTotalTranList?validatekey=" . eastLogin::getToken();

        $LastestDate = self::getLatestDate();

        if ($LastestDate == '') $LastestDate = '2017-06-01';

        for ($i = 0; $i < 100; $i++) {
            $a = $i + 1;
            $b = $i;

            $params[] = [
                "url"    => $url,
                "params" => [
                    'st'   => date('Y-m-d', strtotime("-$a months")),
                    'et'   => date('Y-m-d', strtotime("-$b months")),
                    'qqhs' => 20,
                    'dwc'  => 1,
                ],
            ];
            Log2::save_run_log(json_encode($params), 'yali_east');

            if (strtotime("-$a months") < strtotime($LastestDate)) break;
        }

        return $params;
    }

}
