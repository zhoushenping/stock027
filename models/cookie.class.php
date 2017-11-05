<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/7
 * Time: 15:52
 */
class cookie
{

    static function setOasCookie($key, $val, $days = 1, $dir = "/")
    {
        $t = ($days == 0) ? 0 : time() + 3600 * 24 * $days;
        setcookie($key, $val, $t, $dir, OAS_DOMAIN);
    }

    static function deleteOasCookie($key, $val = 0, $days = -1, $dir = "/")
    {
        setcookie($key, $val, time() + 3600 * 24 * $days, $dir, OAS_DOMAIN);
    }

    static function parseCookieStr($str)
    {
        foreach (explode('; ', $str) as $item) {
            list($k, $v) = explode('=', $item);
            $ret[$k] = urldecode($v);
        }

        return $ret;
    }

    static function makeCookieStr($arr)
    {
        foreach ($arr as $k => $v) {
            $temp[] = "$k=" . urlencode($v);
        }

        return implode('; ', $temp);
    }

    //获取响应cookie的专用curl方法,其他类不一定能用
    static function curl($url, $data = '', $headers = [])
    {
        if (strpos($url, 'http') === false) {
            $url = 'http:' . $url;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, 1);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 6);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $resultData = curl_exec($curl);

        preg_match_all('|Set-Cookie: (.*);|U', $resultData, $matchs);

        curl_close($curl);

        return self::parseCookieStr(implode('; ', $matchs[1]));
    }
}
