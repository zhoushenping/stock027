<?php
/*数据库配置信息*/

$arrDBconf['online']  = array('host' => '192.168.10.24', 'port' => 3306, 'user' => 'shenqu_br', 'pwd' => 'shenqu_br123', 'db_name' => 'shenqu_br',);
$arrDBconf['online'] = array('host' => '127.0.0.1',      'port' => 3306, 'user' => 'root',      'pwd' => '123456',       'db_name' => 'zspstock',);

$oas_db_conf               = $arrDBconf[DB_SET];
$oas_db_conf['db_charset'] = 'utf8';

$cache_server = array('127.0.0.1' => 11211);
