<?php
if (!empty($_POST)) {
    $cookie = eastLogin::login($_POST);
    eastLogin::saveLoginInfo(cookie::makeCookieStr($cookie));
    if (!empty($cookie['Uid'])) {
        Browser::headerRedirect('?a=east');
    }
}
