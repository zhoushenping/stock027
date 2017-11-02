<?

class eastLogin
{

    const table = 'east_login_record';
    static $zhanmoUidList = [
        '1002' => 'uLx/YZzHmtx8kuXy95ohAg==',
    ];
    static $uid           = 1002;

    static function getToken()
    {
        $info = self::getLoginInfo(self::$uid);

        return $info['token'];
    }

    static function getHeaders()
    {
        $info  = self::getLoginInfo(self::$uid);
        $ret   = [];
        $ret[] = "Cookie: " . cookie::makeCookieStr($info);

        return $ret;
    }

    static function saveLoginInfo()
    {
        $cookie_r           = cookie::parseCookieStr($_REQUEST['cookie_r']);
        $data               = $cookie_r;
        $data['token']      = $_REQUEST['token_r'];
        $data['zhanmo_uid'] = self::getZhanmoUid($cookie_r['Uid']);

        DBHandle::insertMulti(self::table, array_keys($data), [$data]);
    }

    static function getLoginInfo($uid)
    {
        String::_filterNoNumber($uid);
        $rs = DBHandle::select(self::table, "`zhanmo_uid`=$uid ORDER BY `id` DESC LIMIT 0,1");

        return $rs[0];
    }

    static function getZhanmoUid($eastUid)
    {
        foreach (self::$zhanmoUidList as $k => $v) {
            if (md5($v) == md5($eastUid)) return $k;
        }

        return 0;
    }

}
