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

    static function saveLoginInfo($str_cookie)
    {
        $cookie_r           = cookie::parseCookieStr($str_cookie);
        $data               = $cookie_r;
        $data['token']      = '';
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

    //////////////////以下为登录curl相关内容

    static $loginUrl = 'https://jy.xzsec.com/Login/Authentication?validatekey=';

    static $loginHeaders = [
        'Referer: https://jy.xzsec.com/Login?el=1&clear=&returl=%2fSearch%2fHisDeal',
        'X-Requested-With: XMLHttpRequest',
        'Accept: application/json, text/javascript, */*; q=0.01',
        'Origin: https://jy.xzsec.com',
        'Host: jy.xzsec.com',
    ];

    static function login($data)
    {
        return cookie::curl(
            self::$loginUrl,
            $data,
            self::$loginHeaders
        );
    }

}
