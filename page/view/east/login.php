<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/20
 * Time: 上午11:26
 */
$eastLoginUrl = "https://jy.xzsec.com/Search/HisDeal";
$js           = "
        var token = $('#em_validatekey').val();
        var cookie = document.cookie;
        $('body').append(\"<form id='zhanmo' action='https://" . WEB_HOST . "/?a=east&m=login' method='post'><input type='hidden' name='token_r' id='token_r'/><input type='hidden' name='cookie_r' id='cookie_r'/></form>\");
        $('#token_r').val(token);
        $('#cookie_r').val(cookie);
        $('#zhanmo').submit();

";
?>
<script src="/static/common/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/static/common/clipboard.min.js"></script>
<textarea name="" id="js_str" cols="100" rows="6" style="width:1px;height:1px;resize: none;">
<?= htmlspecialchars($js) ?>
</textarea>
<b>
    点击“登录”按钮后,在东方财富网站登录后,在交易历史查询页面的控制台粘贴并执行即可
</b>
<button id="d_clip_button1" data-clipboard-target="#js_str" style="font-size: 16px;">登录</button>
<script>
    //与复制相关的代码
    $(function () {
        $('#d_clip_button1').click(function () {
            var clipboard = new Clipboard('#d_clip_button1');

            clipboard.on('success', function (e) {
                window.location = '<?=$eastLoginUrl?>';
            });

            clipboard.on('error', function (e) {
                console.log(e);
            });
        })
    });
</script>

