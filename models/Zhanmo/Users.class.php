<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/6/5
 * Time: 上午11:19
 */
class ZhanmoUsers
{

    const table = 'zhanmo_users';

    static function getAll()
    {
        return DBHandle::select(self::table);
    }

    static function insert($uname, $pwd)
    {
        $uname = preg_replace('/\W/', '', $uname);

        if ($uname == '') return false;

        foreach (self::getAll() as $item) {
            if ($uname == $item['uname']) {
                return false;
            }
        }

        DBHandle::insertMulti(self::table, ['uname', 'pwd'], [[$uname, md5($pwd)]]);

        return true;
    }

    static function updatePwd($pwd)
    {
        if (self::isLogined()) {
            $id  = $_SESSION['uid'];
            $md5 = md5($pwd);

            DBHandle::update(self::table, "`pwd`='$md5'", "`id`=$id");
        }
        else {
            die('用户尚未登录');
        }
    }

    static function login($uname, $pwd)
    {
        foreach (self::getAll() as $item) {
            if ($uname == $item['uname']) {
                if ($item['pwd'] == md5($pwd)) {
                    $_SESSION['uid'] = $item['id'];

                    return true;
                }
            }
        }

        return false;
    }

    static function isLogined()
    {
        return $_SESSION['uid'] != false;
    }

    static function logout()
    {
        $_SESSION['uid'] = false;
    }
}
