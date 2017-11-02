/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/18
 * Time: 下午4:13
 *
 *
 (function() {
var s = document.createElement('script');
s.type = 'text/javascript';
s.async = true;
s.src = '//stock027.oasgames.com/load.js';
var x = document.getElementsByTagName('script')[0];
x.parentNode.insertBefore(s, x);
})();
 *
 */
$(function () {
    var stockUrl = '//stock027.oasgames.com/zsp.php';
    // $('#welcome-to-the-doctrine-project').append("<iframe id='stock027' src='" + stockUrl + "' frameborder='0'></iframe>");
    $('body').prepend("<iframe id='stock027' src='" + stockUrl + "' frameborder='0'></iframe>");
    $('#stock027').css({width: '900px', height: '150px', position: 'fixed', 'z-index': 20});
})


