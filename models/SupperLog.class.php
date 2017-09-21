<?php

class SupperLog
{

    public function SupperLog()
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
    }

    /**
     *    将日志信息写入到文本文件
     */
    private static function write_log($info, $file_name)
    {
        $path = '/data/supperlog/';
        if (file_exists($path) == false) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        $fp  = fopen("$path/$file_name", "a");
        chmod("$path/$file_name", 0777);
        $log = "[" . date("Y-m-d H:i:s") . "]|" . $info . "\r\n";
        fwrite($fp, $log);
        fclose($fp);
    }
}
