<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/10/22
 * Time: 下午11:54
 */
?>

<?
$symbols_shown = [];
foreach (eastHistory::getRecords() as $item) {
    if ($item['Gfye'] == 0) {
        $symbols_shown[] = $item['Zqdm'];
    }

    if ($item['Gfye'] > 0) {
        if (in_array($item['Zqdm'], $symbols_shown)) continue;
        $symbols_shown[] = $item['Zqdm'];
        $symbol          = String::filterNoNumber($item['Zqdm']);

        ?>
        <div>

            <iframe src="//stockpage.10jqka.com.cn/HQ_v4.html#hs_<?= $symbol ?>" width="620" height="610"
                    style="margin:5px;"
                    marginheight="0"
                    marginwidth="0" frameborder="0" scrolling="no"></iframe>
            <span><?= $item['Zqmc'] ?></span>
        </div>
        <?
    }
}
?>
<style>
    iframe {
        display: inline-block;
        transform: scale(0.3, 0.3);
        position: relative;
        top: -212px;
        left: -215px;
    }

    div {
        position: relative;
        width: 200px;
        height: 200px;
        background-color: #666699;
        display: inline-block;
        overflow: hidden;
    }

    span {
        position: absolute;
        bottom: 7px;
        left: 5px;
    }
</style>
