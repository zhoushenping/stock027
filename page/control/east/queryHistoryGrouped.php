<?php
include_once(dirname(__FILE__) . '/define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

$rs = eastHistory::getRecords();

$columns = [
//    'Zqdm',
//    'Zqmc',
'Cjsj',
'Cjjg',
'Cjsl',
'Cjje',
'Sxf',
'Zjfss',
'Gfye',
];

$symbols = [];
$names   = [];
foreach ($rs as $item) {
    $symbols[]            = $item['Zqdm'];
    $names[$item['Zqdm']] = $item['Zqmc'];
}
$symbols = array_unique($symbols);

$t0 = strtotime('2016-10-10 10:22:00');
$t1 = strtotime('2018-10-30 15:00:59');
