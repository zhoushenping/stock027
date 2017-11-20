<?
function getColor($amp)
{
    $percent = (abs($amp) + 1) / 10;

    return $amp >= 0 ? "rgba(255,0,0,$percent)" : "rgba(0,80,0,$percent)";
}

?>
<div style="margin: 0 auto;width:500px;height:100px;">
    <form action="./?a=east&m=amp_fenbu" method="post">
        市值:
        <input type="text" name='marketValue_low' value="<?= $marketValue_low_r ?>">-
        <input type="text" name='marketValue_high' value="<?= $marketValue_high_r ?>">亿<br/>
        市盈率:
        <input type="text" name='syl_low' value="<?= $syl_low_r ?>">-
        <input type="text" name='syl_high' value="<?= $syl_high_r ?>"><br/>
        <button>查询</button>
    </form>
</div>
<table>
    <tr>
        <td style="width:100px;">涨跌幅区间</td>
        <td style="width:300px;"></td>
        <td style="width:60px;">数量</td>
        <td style="width:80px;">百分比</td>
    </tr>

    <?
    $url_template = "./?a=east&m=bigSale3&marketValue_low=$marketValue_low_r&marketValue_high=$marketValue_high_r";
    $url_template .= "&syl_low=$syl_low_r&syl_high=$syl_high_r";
    $zhang = 0;
    $die   = 0;
    foreach ($analyse_result as $k => $v) {
        $percent      = Number::getPercent($v, $total);
        $widthPercent = Number::getPercent($v, $max);
        $divClass     = $k < 0 ? 'green' : 'red';

        $ampColor = getColor($k);

        if ($k < 0) {
            $die += $v;
        }
        else {
            $zhang += $v;
        }

        $amp_low  = $k;
        $amp_high = $k + 1;

        if ($k == 9) {
            $amp_high = 100;
        }

        if ($k == -10) {
            $amp_low = -100;
        }

        ?>
        <tr class="<?= $divClass ?>">
            <td style="background-color: <?= $ampColor ?>"><?= $k ?>%-<?= $k + 1 ?>%</td>
            <td>
                <div style="width: <?= $widthPercent ?>%;height: 8px;"></div>
            </td>
            <td><?= $v ?></td>
            <td>
                <a target="_blank" href="<?= $url_template ?>&low_amp=<?= $amp_low ?>&high_amp=<?= $amp_high ?>">
                    <?= $percent ?>%
                </a>
            </td>
        </tr>
        <?
    }
    ?>

    <tr>
        <td>涨+平</td>
        <td>
            <?= Number::getPercent($zhang, $total, 0) ?>%
        </td>
        <td><?= $zhang ?>/<?= $total ?></td>
        <td></td>
    </tr>
    <tr>
        <td>跌</td>
        <td>
            <?= Number::getPercent($die, $total, 0) ?>%
        </td>
        <td><?= $die ?>/<?= $total ?></td>
        <td></td>
    </tr>
</table>

<style>
    table {
        /*width: 800px;*/
        margin: 50px auto;
        text-align: center;
    }

    td, table {
        border-collapse: collapse;
    }

    td {
        padding: 3px 0px;
        border: 1px solid deepskyblue;
    }

    div {
        border-radius: 3px;
    }

    .red div {
        background-color: rgba(255, 0, 0, 0.9);
    }

    .green div {
        background-color: rgba(0, 80, 0, 0.9);
    }

    tr {
        cursor: pointer;
    }

    tr:hover {
        background-color: gray;
    }
</style>
