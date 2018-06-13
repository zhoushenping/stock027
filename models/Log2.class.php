<?php

class Log2
{

    public function Log2()
    {
    }

    /**
     *    记录业务相关的日志 统一格式
     */
    public static function save_run_log($info, $file_pre_name = "")
    {
        if (empty($file_pre_name)) {
            $file_name = date("Ymd") . ".log";
        }
        else {
            $file_name = $file_pre_name . "_" . date("Ymd") . ".log";
        }
        self::write_log($info, $file_name);

        if (strpos($info, 'jy.xzsec.com') !== false) {
            $file_name = "yali_east_" . date("Ymd") . ".log";
            self::write_log($info, $file_name);
        }
    }

    /**
     *    记录系统出错的日志
     */
    public static function save_err_log($info)
    {
        $file_name = "error_" . date("Ymd") . ".log";
        self::write_log($info, $file_name);
    }

    /**
     *    将日志信息写入到文本文件
     */
    private static function write_log($info, $file_name)
    {
        //如果是绿洲游戏的IP，则记录所有日志，以便分析。
        if (Browser::isOasIP()) {
//            self::write_alllog($info);//todo
        }
        $info = ZHUISUMA . '|' . $info;//日志中加上追溯码
        $info = str_replace("\n", "", $info);//日志中去掉换行

        //不记录特定的日志  减少日志体积
        if (
            strpos($info, 'hq.sinajs.cn/etag.php')                            //每5秒下载实时数据
            or strpos($info, '|0|select')            //数据库的成功查询记录
            or strpos($info, '|0|SELECT')            //数据库的成功查询记录
            or strpos($info, '|0| select')           //数据库的成功查询记录
            or strpos($info, '|0| SELECT')           //数据库的成功查询记录
        ) {
            if (!DEVELOP_MODE) return false;
        }

        $path = LOG_DIR;
        if (file_exists($path) == false) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        $fp = fopen("$path/$file_name", "a");
        chmod("$path/$file_name", 0777);
        $log = "[" . date("Y-m-d H:i:s") . "]|" . $info . "\r\n";
        fwrite($fp, $log);
        fclose($fp);
    }

    private static function write_alllog($info)
    {
        $info = ZHUISUMA . '|' . $info;//日志中加上追溯码
        $info = str_replace("\n", "", $info);//日志中去掉换行

        $file_name = "all_log" . date("Ymd") . ".log";

        $path = LOG_DIR;
        if (file_exists($path) == false) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        $fp = fopen("$path/$file_name", "a");
        chmod("$path/$file_name", 0777);
        $log = "[" . date("Y-m-d H:i:s") . "]|" . $info . "\r\n";
        fwrite($fp, $log);
        fclose($fp);
    }
}
