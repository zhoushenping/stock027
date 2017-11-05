<?
$rand = Random::getRandom();
?>
<script src="/static/common/jquery-1.7.2.min.js"></script>
<script>
    $(function () {
        $('#textIdentifyCode').focus();
    })
</script>
<p>请登录东方财富</p>
<form action="" method="post">
    <input type="hidden" name="userId" value="541200145700"/>
    <input type="hidden" name="password" value="601428"/>
    <input type="hidden" name="randNumber" value="<?= $rand ?>"/>
    <input type="hidden" name="duration" value="86400"/>
    <input type="hidden" name="authCode" value=""/>
    <input type="hidden" name="type" value="Z"/>

    <img src="https://jy.xzsec.com/Login/YZM?randNum=<?= $rand ?>" alt="">
    <input type="text" name="identifyCode" id="textIdentifyCode"/>
    <button type="submit">登录</button>
</form>
