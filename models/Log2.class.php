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
            strpos($info, 'pay.oasgames.com/core/_action.php?msg=getBlackList')                            //支付    查询知否为黑名单玩家
            or (strpos($info, '|200|https://graph.facebook.com/')      and strpos($info, '|200|http'))     //获取玩家的facebook个人信息或好友信息 的成功记录
            or (strpos($info, 'oasgames.com/ranklist')                 and strpos($info, '|200|http'))     //排行榜查询  的成功记录
            or (strpos($info, 'serverlist4guanwan')                    and strpos($info, '|200|http'))     //查询服务器列表 的成功记录
            or (strpos($info, 'loginselectlist?username')              and strpos($info, '|200|http'))     //查询用户在服务器内的角色 的成功记录
            or (strpos($info, 'api_admin_msg_count')                   and strpos($info, '|200|http'))     //查询用户的未读vip消息 的成功记录
            or (strpos($info, 'a=vip&m=getVip')                        and strpos($info, '|200|http'))     //查询用户是否为VIP 的成功记录
            or (strpos($info, 'payment/api/GetUserStatus.php')         and strpos($info, '|200|http'))     //查询用户支付情况的成功记录
            or (strpos($info, 'getUserSecurityLevel')                  and strpos($info, '|200|http'))     //查询用户安全等级的成功记录
            or (strpos($info, 'api/vip_userinfo.php')                  and strpos($info, '|200|http'))     //查询用户vip信息的成功记录
            or (strpos($info, 'api/vip_user_sign_info.php')            and strpos($info, '|200|http'))     //查询用户签到信息的成功记录
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
