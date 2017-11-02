<?

class eastTodayTrade
{

    const  table = 'handle_today_east';

    static $arr_columns = [
        "uid"     => "用户id",//add
        "time"    => "时间戳",//add


        "Cjsj"    => "成交时间",
        "Zqdm"    => "证券代码",
        "Zqmc"    => "证券名称",
        "Cjsj2"   => "成交时间2",
        "Mmsm"    => "委托方向",
        "Cjjg"    => "成交价格",
        "Cjsl"    => "成交数量",
        "Cjje"    => "成交金额",
        "Cjbh"    => "成交编号",
        "Market"  => "市场编号",
        "Wtbh"    => "委托编号",
        "Gddm"    => "股东账号",
        "Dwc"     => "Dwc",
        "Qqhs"    => 'Qqhs',
        "Cjrq"    => "成交日期",
        "Htxh"    => "流水号",
        "Cpbm"    => "Cpbm",
        "Cpmc"    => "Cpmc",
        "Cjlx"    => "0",
        "Wtsl"    => "委托数量",
        "Wtjg"    => "委托价格",
        "Sbhtxh"  => "Y3025806",
        "Zqyjlx"  => "Zqyjlx",
        "Mmlb_ex" => "Mmlb_ex",
        "Mmlb_bs" => "Mmlb_bs",
        "Mmlb"    => "Mmlb",
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
                $tmp[] = $item[$k];
            }

            $arr_data[] = $tmp;
        }
        Log2::save_run_log(count($records), 'bbbb');

        DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
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
            $d_my = self::$beginDate[eastLogin::$currentUser];
            if (Calendar::isDateBefore($dl, $d_my)) continue;

            $ret[$month] = [$d0, $dl];
        }

        return $ret;
    }

    static function getRecords()
    {
        $uid = eastLogin::$uid;

        return DBHandle::select(self::table, "`uid`=$uid ORDER BY `time` DESC");
    }
}
