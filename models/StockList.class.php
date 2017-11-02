<?php

class StockList
{

    const table = 'stock_list';

    //各种类型分别有多少页
    //5为富余量
    static $pageInfo = array(
        'sh_a' => 16 + 5,//沪市A股
        'sz_a' => 26 + 5,//深市A股
        //创业板等板块暂不收录
    );

    //顺序不可随意
    static $arr_columns = [
        'symbol' => '代码',
        'name'   => '名称',
    ];

    static function getSymbols($limit = 0)
    {
        static $rs = [];
        if (empty($rs)) {
            $rs = DBHandle::select(self::table, 1, "distinct `symbol`");
        }

        $ret = [];
        foreach ($rs as $item) {
            $ret[] = $item['symbol'];
        }

        return $limit == 0 ? $ret : array_slice($ret, 0, $limit);
    }

    static function getStandardSymbol($str)
    {
        if (is_numeric($str)) {
            foreach (self::getSymbols() as $symbol) {
                if (substr($symbol, 2) == $str) return $symbol;
            }
        }

        return $str;
    }

    static function update()
    {
        $params      = self::makeDownloadParams();
        $ret         = CURL::mfetch($params);
        $arr_columns = array_keys(self::$arr_columns);

        foreach ($params as $key => $null) {
            $content = $ret[$key]['content'];
            $info    = self::getFormmatedInfo($content);

            if ($info == false) continue;

            $arr_data = [];
            foreach ($info as $temp) {
                $item       = [];
                $item[]     = $temp['symbol'];
                $item[]     = $temp['name'];
                $arr_data[] = $item;
            }
            if (empty($arr_data)) continue;
            DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
        }
    }

    private static function makeDownloadParams()
    {
        $params = [];
        foreach (self::$pageInfo as $node => $pageCount) {
            for ($i = 1; $i <= $pageCount; $i++) {
                $params[] = array(
                    "url"    => "http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData",
                    "params" => array(
                        'page'   => $i,
                        'num'    => 80,
                        'sort'   => 'symbol',
                        'asc'    => '1',
                        'node'   => $node,
                        'symbol' => '',
                        '_s_r_a' => 'page',
                    ),
                );
            }
        }

        return $params;
    }

    private static function getFormmatedInfo($content)
    {
        $content = iconv("GBK", "UTF-8", $content);
        //替换15:00:00这样的字符为空 一面干扰后面的纠正json格式   todo  以后优化
        $content = preg_replace("/\\d{2}:\\d{2}:\\d{2}/", "", $content);
        $content = str_replace('{', '{"', $content);
        $content = str_replace(',', ',"', $content);
        $content = str_replace(':', '":', $content);
        $content = str_replace('},"{', '},{', $content);

        $info = json_decode($content, 1);

        return $info;
    }
}
