<?php

class DingTalk
{

    static $corpid     = "dingeeca56353125822935c2f4657eb6378f";
    static $corpsecret = "HE_BDkt5I2otGExAPdYt-SiyehiiwXAzm3Xf8HVq-hfEPfvQPdbqJooYAVYfRQB9";

    static function send($mobile = '17090083817', $msg = '33525')
    {
        $userlist = self::getUserList();
        $token    = self::getToken();

        $url        = "https://oapi.dingtalk.com/message/send?access_token=$token";
        $post_array = [
            "touser"  => $userlist[$mobile],
            "agentid" => "80216503",
            "msgtype" => "text",
            "text"    => [
                "content" => $msg,
            ],
        ];

        self::postJson($url, $post_array);
    }

    static function postJson($url, Array $arr = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        $arr_header = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($arr)),
        ];

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arr_header);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    static function getUserList()
    {
        $memKey = self::getMemKey('UserList');
        $ret    = Mem::get($memKey);
        $token  = self::getToken();

        if ($ret == false) {
            $ret = [];
            foreach (self::getCorpInfo() as $depart) {
                $url = "https://oapi.dingtalk.com/user/list?access_token=$token&department_id={$depart['id']}";
                $rs  = CURL::getJson($url);
                foreach ($rs['userlist'] as $user) {
                    $ret[$user["mobile"]] = $user["userid"];
                }
            }

            Mem::set($memKey, $ret, 3600);
        }

        return $ret;
    }

    static function getCorpInfo()
    {
        $memKey = self::getMemKey('CorpInfo');
        $ret    = Mem::get($memKey);

        if ($ret == false) {
            $url = "https://oapi.dingtalk.com/department/list?access_token=" . self::getToken();
            $rs  = CURL::getJson($url);
            $ret = $rs['department'];

            Mem::set($memKey, $ret, 86400);
        }

        return $ret;
    }

    static function getToken()
    {
        $memKey = self::getMemKey('token');
        $ret    = Mem::get($memKey);

        if ($ret == false) {
            $url = 'https://oapi.dingtalk.com/gettoken?corpid=' . self::$corpid . '&corpsecret=' . self::$corpsecret;
            $rs  = CURL::getJson($url);
            $ret = $rs['access_token'];

            Mem::set($memKey, $ret, 3600);
        }

        return $ret;
    }

    static function getMemKey($str)
    {
        return "DingTalk{$str}";
    }
}
