<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/19
 * Time: 10:13
 */
class CURL
{

    static function Request($url, $data = '', $headers = [])
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
        curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);//20150819为解决s273的bug而加
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 6);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $resultData = curl_exec($curl);
        $httpInfo   = curl_getinfo($curl);
        Log2::save_run_log("{$httpInfo['http_code']}|$url|$resultData", 'curl2');
        if (curl_errno($curl)) {
            curl_close($curl);
            $ret = false;
        }
        else {
            curl_close($curl);
            $ret = $resultData;
        }

        return $ret;
    }

    static function getJson($url, $data = '', $headers = [])
    {
        $ret = array();
        $rs  = self::Request($url, $data, $headers);
        if ($rs !== false) {
            $rs = json_decode($rs, 1);
            if (is_array($rs)) {
                $ret = $rs;
            }
        }

        return $ret;
    }

    static function mfetch($params, $method = 'get', $headers = [])
    {
        $mh      = curl_multi_init(); //初始化一个curl_multi句柄
        $handles = array();
        foreach ($params as $key => $param) {
            $ch   = curl_init(); //初始化一个curl句柄
            $url  = $param["url"];
            $data = $param["params"];
            if (strtolower($method) === "get") {
                //根据method参数判断是post还是get方式提交数据
                $url = "$url?" . http_build_query($data); //get方式
            }
            else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //post方式
            }
            Log2::save_run_log($url, 'curl_m');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_multi_add_handle($mh, $ch);
            $handles[$ch] = $key;
            //handles数组用来记录curl句柄对应的key,供后面使用，以保证返回的数据不乱序。
        }
        $running = null;
        $curls   = array(); //curl数组用来记录各个curl句柄的返回值
        do { //发起curl请求，并循环等等1/100秒，直到引用参数"$running"为0
            usleep(10000);
            curl_multi_exec($mh, $running);
            while (($ret = curl_multi_info_read($mh)) !== false) {
                //循环读取curl返回，并根据其句柄对应的key一起记录到$curls数组中,保证返回的数据不乱序
                $curls[$handles[$ret["handle"]]] = $ret;
            }
        } while ($running > 0);

        foreach ($curls as $key => &$val) {
            $val["content"] = curl_multi_getcontent($val["handle"]);
            curl_multi_remove_handle($mh, $val["handle"]); //移除curl句柄
        }
        curl_multi_close($mh); //关闭curl_multi句柄
        ksort($curls);

        return $curls;
    }
}
