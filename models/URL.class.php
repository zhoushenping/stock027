<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2016/3/15
 * Time: 12:26
 */
class URL
{
    const home       = 'http://loar.oasgames.com/';
    const fans       = 'https://www.facebook.com/Legend.Online.AR';
    const forum      = 'http://ar.forum.oasgames.com/index.php';
    const ask        = 'http://ask.oasgames.com/ar';
    const jifen      = 'http://shop.oasgames.com/?lang=ar';
    const twitter    = 'https://twitter.com/_LegendOnline_';
    const ucenter    = '//www.oasgames.com/?a=ucenter&m=myinfo&lang=ar-ar&source=loarosite';
    const home_lunbo = '//plugins.oasgames.com/lunbo/weget/index.php?type_id=14390';
    const findpw     = 'http://www.oasgames.com/index.php?a=ucenter&m=findpwd&lang=ar-ar';
    const blog       = 'http://ar.blog.oasgames.com/';
    const vip_desc   = "http://www.oasgames.com/?a=newucenter&m=systemvip";//vip介绍页面

    static function getCurrent()
    {
        return
            ($_SERVER['SERVER_PORT'] == 80 ? 'http' : 'https')
            . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }

    static function getGAclassByReferer()
    {
        $refer = $_SERVER['HTTP_REFERER'];
        if (strpos($refer, 'card') !== false) {
            return 'com2';
        }
        if (strpos($refer, 'serverlist') !== false) {
            return 'com2';
        }

        return 'com1';
    }
}
