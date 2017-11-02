<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/22
 * Time: 下午11:54
 */

?>

<?
$name          = '天神娱乐';
$symbols_shown = ['002354', '002354', '002354', '002354', '002354', '002354', '002354', '002354', '002354',];
foreach ($symbols_shown as $symbol) {
    ?>
    <div class="chart_container">
        <div class="chart_mask"></div>
        <iframe src="//stockpage.10jqka.com.cn/HQ_v4.html#hs_<?= $symbol ?>" width="620" height="610"
                style="margin:5px;"
                marginheight="0"
                marginwidth="0" frameborder="0" scrolling="no"></iframe>
        <span><?= $name ?></span>
    </div>
    <?
}
?>
<script src="/static/common/jquery-1.7.2.min.js"></script>
<script>
    $(function () {
        $('.chart_mask').click(function () {
            alert(123);
        })
    })
</script>
<style>
    iframe {
        display: inline-block;
        transform: scale(0.6, 0.6);
        position: relative;
        top: -122px;
        left: -125px;
    }

    div.chart_container {
        position: relative;
        width: 380px;
        height: 350px;
        background-color: #666699;
        display: inline-block;
        overflow: hidden;
    }

    span {
        position: absolute;
        bottom: 7px;
        left: 5px;
    }

    .chart_mask {
        position: absolute;
        z-index: 5;
        width: 100%;
        height: 100%;
        cursor: pointer;
        /*background-color: red;*/
    }
</style>
