<?php
include_once(dirname(__FILE__) . '/define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

$a = $_REQUEST['a'];
$m = $_REQUEST['m'];

$a = ($a != '') ? $a : 'index';
$m = ($m != '') ? $m : 'index';

if (file_exists("./page/control/{$a}/{$m}.php")) {
    require_once "./page/control/{$a}/{$m}.php";
    require_once "./page/view/$a/{$m}.php";
    die;
}

echo "welcome to zsp stock website";
