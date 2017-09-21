<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/19
 * Time: 16:25
 */
class Browser
{
    static function getRequestPort()
    {
        return self::isHttps() ? 443 : 80;
    }

    static function isHttps()
    {
        $port = $_SERVER['SERVER_PORT'];

        return ($port == 443);
    }

    static function isIE()
    {
        $strpos_ie = strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), 'msie');

        return ($strpos_ie !== false);
    }

    static function isIE7()
    {
        $strpos_ie = strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), 'msie 7');

        return ($strpos_ie !== false);
    }

    static function isIE8()
    {
        $strpos_ie = strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), 'msie 8');

        return ($strpos_ie !== false);
    }

    static function getIE_Version()
    {
        preg_match("/MSIE\s+([^;)]+)+/i", $_SERVER['HTTP_USER_AGENT'], $ie);//返回结果类似于 array("MSIE 10.0","10.0");

        return intval($ie[1]);
    }

    static function getIE_Info()
    {
        preg_match("/MSIE\s+([^;)]+)+/i", $_SERVER['HTTP_USER_AGENT'], $ie);//返回结果类似于 array("MSIE 10.0","10.0");

        return $ie[0];
    }

    static function topRedirect($url)
    {
        echo "
        <script>
            window.top.location = '$url';
        </script>";
        exit;
    }

    static function headerRedirect($url)
    {
        header("Location:$url");
        exit;
    }

    static function tryForIE($uid, $server_sid)
    {
        $tofull = ($_REQUEST['tofull'] == 1);
        if (self::isIE() || $tofull) {
            $ieInfo    = self::getIE_Info();
            $ieVersion = self::getIE_Version();
            $CLIENT_IP = self::get_client_ip();
            if ($ieVersion < 8 || $tofull) {
                Log2::save_run_log($CLIENT_IP . "|$ieInfo|$uid|$server_sid", 'ie_app_min');
                self::topRedirect(FULL_SCREEN_URL . "?server_id=$server_sid");
            }
            else {
                Log2::save_run_log($CLIENT_IP . "|$ieInfo|$uid|$server_sid", 'ie_app');
            }
        }
    }

    static function  get_client_ip()
    {
        return $_SERVER["REMOTE_ADDR"];
    }

    static function isOasIP()
    {
        if (DEVELOP_MODE) {
            return true;
        }
        $temp = self::get_client_ip();

        $arr_oas_ip = array(
            '124.205.66.66',
            '178.18.19.146',//zspvps
            '104.224.143.240',//zspvps2
            '107.161.95.131',//zspsea2
        );

        return in_array($temp, $arr_oas_ip);
    }

    static function callback($arr)
    {
        $retCode = json_encode($arr);
        if (!empty($_REQUEST['callback'])) {
            $retCode = "{$_REQUEST['callback']}($retCode);";
        }

        echo $retCode;
        exit;
    }
}
