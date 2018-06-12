<?php
include_once(dirname(__FILE__) . '/../define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

set_time_limit(0);

$rs       = eastPosition::getList(true);
$cacheKey = "positionLast" . date('Ymd');

Mem::set($cacheKey, $rs, 86400 * 30);
