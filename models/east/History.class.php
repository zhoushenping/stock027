<?

class eastHistory
{

    const  table = 'handle_history_east';
    static $beginDate = [
        'zhoushenping'  => '2017-09-01',
        'zhangfeixiong' => '2007-09-01',
    ];

    static $arr_columns = [
        "uid"     => "用户id",//add
        "time"    => "时间戳",//add
        "Cjrq"    => "成交日期",
        "Cjsj"    => "成交时间",
        "Cjsj2"   => "成交时间2",
        "Zqdm"    => "证券代码",
        "Zqmc"    => "证券名称",
        "Mmsm"    => "委托方向",
        "Cjjg"    => "成交价格",
        "Cjsl"    => "买进数量",
        "Cjje"    => "成交金额",
        "Sxf"     => "手续费",
        "Yhs"     => "印花税",
        "Ghf"     => "过户费",
        "Zjye"    => "资金余额",
        "Gfye"    => "成交后股份余量",
        "Market"  => "市场编号",
        "Cjbh"    => "成交编号",
        "Wtbh"    => "委托编号",
        "Gddm"    => "股东账号",
        "Dwc"     => "Dwc",
        "Qqhs"    => 'Qqhs',
        "Mmlb"    => "Mmlb",
        "Htxh"    => "流水号",
        "Wtxh"    => "委托序号",
        "Cpbm"    => "Cpbm",
        "Cjxh"    => "成交型号?",
        "Wtsl"    => "委托数量",
        "Wtjg"    => "委托价格",
        "Gfbcye"  => "Gfbcye",
        "Jsxf"    => "佣金",
        "Qsf"     => "Qsf",
        "Jygf"    => "交易规费",
        "Jsf"     => "Jsf",
        "Zgf"     => "Zgf",
        "Qtf"     => "Qtf",
        "Qtfy"    => "Qtfy",
        "Zjfss"   => "金额变动",
        "Syje"    => "sy金额",
        "Zqyjlx"  => "Zqyjlx",
        "Jsrq"    => "Jsrq",
        "Mmlb_ex" => "Mmlb_ex",
        "Mmlb_bs" => "Mmlb_bs",
    ];

    static function downloadMuti()
    {
        $params = self::makeDownloadParams();
        $rs     = CURL::mfetch($params, 'post', eastLogin::getHeaders());

        $arr_columns = array_keys(self::$arr_columns);
        $arr_data    = [];

        $records = self::getFormattedInfo($rs);
        foreach ($records as $item) {
            $tmp = [];
            foreach ($arr_columns as $k) {
                $v     = ($k == 'Zqdm') ? self::getSymbol($item) : $item[$k];
                $tmp[] = $v;
            }

            $arr_data[] = $tmp;
        }
        Log2::save_run_log(count($records), 'bbbb');

        DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);

        return count($arr_data);
    }

    private static function getFormattedInfo($rs)
    {
        $ret = [];
        krsort($rs);
        foreach ($rs as $item) {
            $record = json_decode($item['content'], 1);
            $record = $record['Data'];
            foreach ($record as $iitt) {
                $iitt['uid']  = eastLogin::$uid;
                $iitt['time'] = strtotime("{$iitt['Cjrq']} {$iitt['Cjsj']}");
                $ret[]        = $iitt;
            }
        }

        return $ret;
    }

    private static function makeDownloadParams()
    {
        $params = [];
        $url    = "https://jy.xzsec.com/Search/GetHisDealData?validatekey=" . eastLogin::getToken();

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

    static function getRecords($uid = 0)
    {
        $uid = ($uid == 0) ? eastLogin::$uid : $uid;

        return DBHandle::select(self::table, "`uid`=$uid ORDER BY `time` DESC");
    }

    private static function getSymbol($item)
    {
        return ($item['Market'] == 'HA') ? "sh{$item['Zqdm']}" : "sz{$item['Zqdm']}";
    }
}
