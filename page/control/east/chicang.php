<?php
include_once(dirname(__FILE__) . '/define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

$rs = eastHistory::getRecords();

$columns = [
    'Zqdm',
    'Zqmc',
    'price',
    'Gfye',
    'value',
];
