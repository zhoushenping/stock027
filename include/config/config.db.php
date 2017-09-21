<?php
/*数据库配置信息*/

$arrDBconf['online'] =
    [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'user'    => 'root',
        'pwd'     => 'dream',
        'db_name' => 'stock027',
    ];

$arrDBconf['online'] = [
    'host'    => '127.0.0.1',
    'port'    => 3306,
    'user'    => 'root',
    'pwd'     => '123456',
    'db_name' => 'stock027',
];

$oas_db_conf               = $arrDBconf[DB_SET];
$oas_db_conf['db_charset'] = 'utf8';

$cache_server = array('127.0.0.1' => 11211);
