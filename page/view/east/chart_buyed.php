<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/22
 * Time: 下午11:54
 */
?>
<title>曾持仓</title>
<style>
    iframe {
        display: inline-block;
        zoom: 0.6;
    }

    h4 {
        margin: 0;
    }
</style>
<?
$symbols_shown = [];
foreach (eastHistory::getRecords() as $item) {
    if (in_array($item['Zqdm'], $symbols_shown)) continue;
    $symbols_shown[] = $item['Zqdm'];
    $symbol          = String::filterNoNumber($item['Zqdm']);

    ?>
    <div style="display: inline-block;">
        <h4><?= $item['Zqmc'] ?>_<?= $item['Zqdm'] ?></h4>
        <iframe src="http://stockpage.10jqka.com.cn/HQ_v4.html#hs_<?= $symbol ?>" width="620" height="600"
                style="/*margin:0 auto 50px;*/"
                marginheight="0"
                marginwidth="0" frameborder="0" scrolling="no"></iframe>
    </div>
    <?
}
?>
