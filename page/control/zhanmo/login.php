<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/6/5
 * Time: 上午11:15
 */
$act = $_REQUEST['act'];
if ($act == 'register') {
    $uname   = $_REQUEST['uname'];
    $pwd     = $_REQUEST['pwd'];
    $pwd_rep = $_REQUEST['pwd_rep'];

    if ($pwd == $pwd_rep && ZhanmoUsers::insert($uname, $pwd)) {
        cookie::setOasCookie('zhanmo_uname', $uname, 30);
        echo "register ok";
    }
    else {
        echo "register fail";
    }

    die;
}

if ($act == 'login') {
    $uname = $_REQUEST['uname'];
    $pwd   = $_REQUEST['pwd'];

    if (ZhanmoUsers::login($uname, $pwd)) {
        cookie::setOasCookie('zhanmo_uname', $uname, 30);
        echo "login ok";
    }
    else {
        echo "login fail";
    }
    die;
}

if ($act == 'reset_pwd') {
    $pwd = $_REQUEST['pwd'];

    if (ZhanmoUsers::isLogined()) {
        ZhanmoUsers::updatePwd($pwd);
    }
}
