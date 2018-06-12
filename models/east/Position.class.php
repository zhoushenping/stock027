<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/31
 * Time: 下午11:08
 */
class eastPosition
{

    static function getList($fresh = false)
    {
        $cacheKey = 'positionList';
        $cache    = Mem::get($cacheKey);

        //得到强制刷新指令或者无缓存时  从在线接口读取
        if ($fresh || $cache == false) {
            $params = self::makeDownloadParams();
            $ret    = self::makeGetListRequest($params);
            Mem::set($cacheKey, $ret, 86400 * 20);//记录到缓存

            return $ret;
        }
        else {
            return self::reformatCache($cache);//重新格式化缓存并输出
        }
    }

    //重新格式化缓存的信息  更新其中需要更新的字段
    static function reformatCache($cache)
    {
        $ljyk_last       = self::getLjykLast();
        $cache['Zxsz']   = 0;
        $cache['Ljyk']   = 0;
        $cache['Drckyk'] = 0;
        $last            = eastLast::getLastPriceList();
        foreach ($cache['F303S'] as $k => $item) {
            $symbol = ($item['Market'] == 'SA' ? 'sz' : 'sh') . $item['Zqdm'];

            $cache['F303S'][$k]['Zxjg']   = $last[$symbol];
            $cache['F303S'][$k]['Zxsz']   = $last[$symbol] * $item['Zqsl'];
            $cache['F303S'][$k]['Ykbl']   = Number::getDiffRate($last[$symbol], $item['Cbjg']) / 100;
            $cache['F303S'][$k]['Ljyk']   = $item['Zqsl'] * ($last[$symbol] - $item['Cbjg']);//当前股累计盈亏
            $cache['F303S'][$k]['Drljyk'] =
                number_format($cache['F303S'][$k]['Ljyk'] - $ljyk_last[$symbol], 0);//当前股的当日累计盈亏
            $cache['F303S'][$k]['Drykbl'] =
                number_format($cache['F303S'][$k]['Drljyk'] / $item['Cbjg'] / $item['Zqsl'], 0, 2);

            $cache['Zxsz'] += $last[$symbol] * $item['Zqsl'];
            $cache['Ljyk'] += $cache['F303S'][$k]['Ljyk'];//总累计盈亏
            $cache['Drckyk'] += $cache['F303S'][$k]['Drljyk'];//累加各股的当日参考盈亏
        }

        $cache['Zzc'] = $cache['Djzj'] + $cache['Kyzj'] + $cache['Zxsz'];

        return $cache;
    }

    private function getLjykLast()
    {
        $ljyk_last  = [];
        $cache_last = [];
        for ($i = 1; $i <= 20; $i++) {
            $cacheKey_last = "positionLast" . date('Ymd', strtotime("-$i days"));
            $cache_last    = Mem::get($cacheKey_last);

            if (!empty($cache_last)) break;
        }
        foreach ($cache_last['F303S'] as $item) {
            $symbol             = ($item['Market'] == 'SA' ? 'sz' : 'sh') . $item['Zqdm'];
            $ljyk_last[$symbol] = $item['Ljyk'];
        }

        return $ljyk_last;
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

        return $ret[0];
    }

    private static function makeDownloadParams()
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

    //查询可用资金
    static function getKyzj()
    {
        $positionInfo = self::getList();

        return $positionInfo['Kyzj'];//可用资金
    }
}
