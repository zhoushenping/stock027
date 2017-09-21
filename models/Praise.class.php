<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2016/3/15
 * Time: 11:10
 */
class Praise
{
    static $config = array(
        'fb_url' => "//www.facebook.com/plugins/like.php?href=https://www.facebook.com/Legend.Online.AR&width&layout=button_count&action=like&show_faces=true&share=false&height=21",
        'gg_url' => "https://plus.google.com/108744501959939857732",
        'gg_js'  => "<script type=\"text/javascript\">
                         (function() {
                         var po = document.createElement('script'); po.type='text/javascript'; po.async=true;
                         po.src = 'https://apis.google.com/js/plusone.js';
                         var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                         })();
                     </script> ",
        'tw_url' => "//platform.twitter.com/widgets/follow_button.1392079123.html#_=1392192123245&amp;id=twitter-widget-0&amp;lang=tr&amp;screen_name=_LegendOnline_&amp;show_count=true&amp;show_screen_name=false&amp;size=m",
        'support' => "http://support.oasgames.com/?a=question&m=add&game_code=lobr&lang=pt",//
    );
}
