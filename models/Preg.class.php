<?

class Preg
{
    //从字符串中匹配出uid 返回一维数组
    static function matchUid($str)
    {
        $ret = array();
        if ($str == '') {
            return $ret;
        }

        if (DEVELOP_MODE) {
            preg_match_all('/[0-9]{4,30}/', $str, $match);
        }
        else {
            preg_match_all('/[0-9]{6,30}/', $str, $match);
        }

        return array_unique($match[0]);
    }

    static function matchCode($str)
    {
        $ret = array();
        if ($str == '') {
            return $ret;
        }

        if (DEVELOP_MODE) {
            preg_match_all('/[a-z0-9A-Z-_]{4,30}/', $str, $match);
        }

        if (!DEVELOP_MODE) {
            preg_match_all('/[a-z0-9A-Z]{10,30}/', $str, $match);
        }

        return array_unique($match[0]);
    }

    static function isOasUid($uid)
    {
        return 1 == preg_match('/20000\d{10}/', $uid);//20000 0022039467
    }
}
