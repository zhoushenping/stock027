<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/23
 * Time: 下午11:34
 */
session_start();
error_reporting(E_ALL);
require './code.class.php';  //先把类包含进来，实际路径根据实际情况进行修改。
$_vc = new ValidateCode();  //实例化一个对象
$_vc->doimg();
$_SESSION['authnum_session'] = $_vc->getCode();//验证码保存到SESSION中
