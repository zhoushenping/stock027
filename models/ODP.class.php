<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/18
 * Time: 19:50
 */
class ODP
{
    const CODE_SECRET_KEY = 'dfsdQpMfj1w23425SotegFdfswsMb';

    //根据OAS Login Key 获取玩家信息
    //改动20151210：为了应对此方法同一cookie短时间内N次调用odp接口，使用session存储数据
    static function getUserInfoFromOasLoginKey($token = '')
    {
        //使得可以成为api
        if ($token == '') {
            $token = $_GET['oas_user'];
        }
        if ($token == '') {
            $token = $_COOKIE['oas_user'];
        }
        if (empty($token)) {
            return array();
        }
        $url  = ODP_URL . "/api/?m=user.getLoginUser&game_code=" . GAME_CODE_STANDARD . "&token={$token}&show_all=yes";
        $data = array();

        return self::getLoginInfo($token, $url, $data);
    }

    //根据Facebook Access Token 获取玩家信息
    static function getUserInfoFromFacebookToken($token = '')
    {
        if ($token == '') {
            $token = $_SESSION['access_token'];
        }
        if ($token == '') {
            $token = $_COOKIE['app_access_token'];
        }
        if (myFacebook::isPrivateToken($token) == false) {
            return array();
        }

        $oas_sp_promote = isset($_COOKIE['oas_sp_promote']) ? $_COOKIE['oas_sp_promote'] : '';
        $sp_promote     = isset($_GET['sp_promote']) ? $_GET['sp_promote'] :
            (isset($_GET['oauth_sp_promote']) ? $_GET['oauth_sp_promote'] : $oas_sp_promote);

        $data = array(
            'game_code'    => GAME_CODE_STANDARD,
            'access_token' => $token,
            'show_all'     => 'yes',
            'lang'         => LANG_OAS,
            'sp_promote'   => $sp_promote,
            'ip'           => Browser::get_client_ip(),
        );

        $url = ODP_URL . "/api/?m=user.fbAPPLogin";

        return self::getLoginInfo($token, $url, $data);
    }

    private static function getLoginInfo($token, $url, $data)
    {
        self::treatDumpRequest($url, $data);//处理dump请求

        if ($_SESSION[$token]['auto'] == array()) {
            $rs = CURL::getJson($url, $data);

            if ($rs['status'] == 'ok') {
//                $rs['uid']                = self::getUidAuto($rs);//成功登录的情况下  将处理后的uid加入$rs
                $_SESSION[$token]['auto'] = $rs;//将成功并经过处理的$rs存入session
            }

            return $rs;//吐出$rs
        }
        else {
            return $_SESSION[$token]['auto'];//session中有成功的结果集，直接吐出
        }
    }

    static function getUidAuto($rs)
    {
        $uid = $rs['uid'];
        if (self::isOasUid($rs)) {
            return $uid;
        }//uid为oas uid时直接返回

        $oas_uid    = $rs['all_info']['id'];
        $fb_app_uid = self::getFBappUid($rs['all_info']['business_map']);
        $fb_sns_uid = $rs['all_info']['snsUid'];

        //如果$fb_app_uid $fb_sns_uid中有一个为空，则返回uid 不使用复杂的逻辑处理
        if (empty($fb_app_uid) || empty($fb_sns_uid)) {
            return $uid;//得到$uid
        }

        //两者相同时 直接返回其中一个 不使用复杂的逻辑处理
        if ($fb_app_uid == $fb_sns_uid) {
            return $fb_app_uid;
        }

        $temp_uid = GetUidAuto::get($oas_uid);
        if ($temp_uid != '' and $temp_uid != 99) {
            return $temp_uid;
        }//如果已有逻辑记录  则返回查出的结果

        $ret = (PLATFORM == 'app') ? $fb_app_uid : $fb_sns_uid;
        if ($temp_uid == 99) {
            return $ret;
        }//按照平台自动分配uid

        $isPlayed              = array();//是否玩过
        $isPlayed[$fb_app_uid] = (PlayGameRecord::getPlayedSids($fb_app_uid, 'full') != array());//传入full 不会过滤大于2000的服
        $isPlayed[$fb_sns_uid] = (PlayGameRecord::getPlayedSids($fb_sns_uid, 'full') != array());//传入full 不会过滤大于2000的服

        if ($isPlayed[$fb_app_uid] and $isPlayed[$fb_sns_uid]) {
            GetUidAuto::add($rs, 99, 1);//$fb_app_uid $fb_sns_uid 都玩过，则存入99这个特定的值，使得下次访问时  按照平台自动分配uid
        }

        //只有一个uid玩过时  取该uid
        if ($isPlayed[$fb_app_uid] and !$isPlayed[$fb_sns_uid]) {
            $ret = $fb_app_uid;
            GetUidAuto::add($rs, $ret, 2);
        }

        if ($isPlayed[$fb_sns_uid] and !$isPlayed[$fb_app_uid]) {
            $ret = $fb_sns_uid;
            GetUidAuto::add($rs, $ret, 3);
        }

        //两个都没玩过    会使用默认值

        return $ret;
    }

    static function isOasUid($rs)
    {
        if ($rs['val']['user_from'] == 1 || $rs['val']['user_from'] == 5) {
            return false;
        }
        else {
            return true;
        }
        //return 1 == preg_match('/20000\d{10}/', $uid);//20000 0022039467
    }

    static function treatDumpRequest($url, $data)
    {
        if ($_REQUEST['dump_user_info'] != 1) {
            return false;
        }
        $rs                = CURL::getJson($url, $data);
        $ret['odp_uid']    = $rs['uid'];
        $ret['oas_uid']    = $rs['all_info']['id'];
        $ret['fb_app_uid'] = self::getFBappUid($rs['all_info']['business_map']);
        $ret['fb_sns_uid'] = $rs['all_info']['snsUid'];
        $ret['regDate']    = date('Y-m-d H:i:s', $rs['all_info']['regdate'] + 3600 * 6);
        echo "<pre/>";
        print_r($ret);
        die;
    }

    static function getFBappUid($input)
    {
        return is_array($input) ? $input[APP_ID]['fb_app_uid'] : '';
    }

    public static function getGiftCode($codeid, $uid, $game_code = GAME_CODE_STANDARD)
    {
        if (empty($codeid)) {
            return '';
        }
        else {
            $param = array(
                'gift_bag_id' => $codeid,
                'uid'         => $uid,
                'game_code'   => $game_code,
                'sign'        => md5($codeid . $game_code . $uid . self::CODE_SECRET_KEY),
            );
            $url   = ODP_URL . '/api/index.php?m=activecode.getCode&' . http_build_query($param);
            $res   = CURL::getJson($url);
        }

        return ($res['status'] == 'ok') ? trim($res['val']) : '';
    }
}
