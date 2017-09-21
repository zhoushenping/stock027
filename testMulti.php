<?php
include_once(dirname(__FILE__) . '/define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

$arr_stock_ids = array(
    'sz002354',
    'sz002174',
    'sz000717',
    'sh600722',
);

$dates = array(
    '2017-09-19',
    '2017-09-18',
    '2017-09-17',
    '2017-09-16',
    '2017-09-15',
    '2017-09-14',
    '2017-09-13',
    '2017-09-12',
    '2017-09-11',
    '2017-09-10',
    '2017-09-09',
    '2017-09-08',
    '2017-09-07',
    '2017-09-06',
    '2017-09-05',
    '2017-09-04',
    '2017-09-03',
);

StockDownload::getMultiDetail($arr_stock_ids, $dates);
