<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/6/5
 * Time: 上午11:15
 */
$act     = $_REQUEST['act'];
$uname   = $_REQUEST['uname'];
$pwd     = $_REQUEST['pwd'];
$pwd_rep = $_REQUEST['pwd_rep'];

if ($act == 'register') {
    if ($pwd == $pwd_rep && ZhanmoUsers::insert($uname, $pwd)) {
        cookie::setOasCookie('zhanmo_uname', $uname, 30);
    }

    $act = 'login';
}

if ($act == 'login') {
    if (ZhanmoUsers::login($uname, $pwd)) {
        cookie::setOasCookie('zhanmo_uname', $uname, 30);
//        Browser::headerRedirect('./?a=east');
        Browser::topRedirect('./?a=east');
    }
}

if ($act == 'reset_pwd') {
    $pwd     = $_REQUEST['pwd'];
    $pwd_rep = $_REQUEST['pwd_rep'];

    if ($pwd == $pwd_rep && ZhanmoUsers::isLogined()) {
        ZhanmoUsers::updatePwd($pwd);
        Browser::headerRedirect('./?a=zhanmo&m=login');
    }
}
