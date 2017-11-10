<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');
require_once(ROOT_PATH . 'include/libs/dama2/Dama2CurlApi.php');

$d = date('Y-m-d');

//if (TradeDate::isTradeDate($d) == false) die;//非工作日  不登录

$fundsFlow = eastFundsFlow::getList();
if (count($fundsFlow) > 0) {
    Log2::save_run_log('already && quit', 'dama2');
    die;//已登录  则不再登录
}
///////////////开始走登录逻辑
$rand = Random::getRandom();

$dama2Handle = new Dama2Api('zhoushenping001', '281255');
$request     = $dama2Handle->decode_url("https://jy.xzsec.com/Login/YZM?randNum=$rand", '42');
Log2::save_run_log(json_encode($request), 'dama2_decode');

$id          = $request['id'];
$imageResult = array('ret' => '-303');
$retry       = 10;
while (true) {
    if ($retry > 0 && isset($imageResult['ret']) && $imageResult['ret'] === '-303') {
        sleep(2);
        $imageResult = $dama2Handle->get_result($id);
        Log2::save_run_log(json_encode($imageResult), 'dama2');
        $retry--;
    }
    else {
        break;
    }
}

$code = $imageResult['result'];
if ($code == '') {
    Log2::save_run_log('login fail', 'dama2');
    die;
}
else {
    Log2::save_run_log('login ok', 'dama2');
}

$post = [
    'userId'       => '541200145700',
    'password'     => '601428',
    'randNumber'   => $rand,
    'duration'     => 1800,
    'authCode'     => '',
    'type'         => 'Z',
    'identifyCode' => $code,
];

$cookie = eastLogin::login($post);

eastLogin::saveLoginInfo(cookie::makeCookieStr($cookie));
