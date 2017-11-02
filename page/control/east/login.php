<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/20
 * Time: 上午11:04
 */
if (!empty($_REQUEST['token_r'])) {
    eastLogin::saveLoginInfo();

    Browser::headerRedirect("?a=east");
}
