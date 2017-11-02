<?php

class Zhuli
{

    const table = 'zhuli_dongxiang';//主力动向

    //获取指定日期范围内每天主力增持、减持 top50
    //样本 download([20171012,20171011,20171004]);
    static function downloadDailyTop50($dates)
    {
        $key    = 1;
        $params = array();
        foreach ($dates as $date) {
            $params[$key] = array(
                "url"    => "http://stock.gtimg.cn/data/view/zldx.php",
                "params" => array(
                    't' => '3',
                    'd' => String::filterNoNumber($date),
                    'r' => Random::getRandom(),
                ),
            );
            $key++;
        }

        $rs = CURL::mfetch($params, 'get');

        foreach ($params as $key => $paraInfo) {
            $date = $paraInfo['params']['d'];

            $content = $rs[$key]['content'];

            $content = iconv("GBK", "UTF-8", $content);
            self::insert($date, self::getFormmatedInfo($content));
        }
    }

    private static function insert($date, $info)
    {
        if (empty($info)) return false;
        $arr_columns = ['symbol', 'amount', 'date'];
        $arr_data    = [];
        foreach ($info as $item) {
            $item[]     = $date;
            $arr_data[] = $item;
        }
        DBHandle::insertMultiIgnore(self::table, $arr_columns, $arr_data);
    }

    private static function getFormmatedInfo($content)
    {
        $ret = [];
        foreach (explode(';', $content) as $line) {
            if ($line == '') continue;
            $line = substr($line, 0, -1);
            $line = substr($line, strpos($line, '10=') + 4);
            if ($line == '') continue;

            foreach (explode('^', $line) as $item) {
                $ret[] = explode('~', $item);
            }
        }

        return $ret;
    }

    //http://stock.gtimg.cn/data/view/ggdx.php?t=3&d=9&q=sz002467,sh601288

    static function downloadGegu($symbols, $dateCount = 30)
    {
        $key    = 1;
        $params = array();
        foreach ([1] as $v) {
            $params[$key] = array(
                "url"    => "http://stock.gtimg.cn/data/view/ggdx.php",
                "params" => array(
                    't' => '3',
                    'd' => $dateCount,
                    'q' => implode(',', $symbols),
                ),
            );
            $key++;
        }

        $rs = CURL::mfetch($params, 'get');

        foreach ($params as $key => $paraInfo) {
            $content = $rs[$key]['content'];
            $content = iconv("GBK", "UTF-8", $content);
            var_dump(self::getFormmatedGeguInfo($content));//todo
//            self::insert($date, self::getFormmatedGeguInfo($content));
        }
    }

    private function getFormmatedGeguInfo($content)
    {
        $ret = [];
        foreach (explode(';', $content) as $line) {
            if ($line == '') continue;
            $line   = substr($line, 0, -1);
            $line   = substr($line, strpos($line, 'flow') + 5);
            $symbol = substr($line, 0, 8);
            $line   = substr($line, 10);

            foreach (explode('^', $line) as $item) {
                $ret[$symbol][] = explode('~', $item);
            }
        }

        return $ret;
    }
}
