(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-33361405-27', 'auto');
ga('send', 'pageview');

function ga_send( _array)
{
    var _len = _array.length;
    if(_len == 2) ga('send', 'event', _array[0], _array[1]);
    if(_len == 3) ga('send', 'event', _array[0], _array[1], _array[2]);
    if(_len == 4) ga('send', 'event', _array[0], _array[1], _array[2], _array[3]);
}

<?
/*
 * 语法参考
 * http://www.analyticskey.com/google-analytics-upgrade-to-universal-analytics/
 *
ga('send', 'event', 'category', 'action');
ga('send', 'event', 'category', 'action', 'label');
ga('send', 'event', 'category', 'action', 'label', value);  // value为一数值.

ga('send', 'event', 'category', 'action', {'nonInteraction': 1});
 * */
?>
