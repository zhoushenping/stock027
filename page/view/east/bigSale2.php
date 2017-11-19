<?
function getColor($amp)
{
    $percent = (abs($amp) + 1) / 10;

    return $amp >= 0 ? "rgba(255,0,0,$percent)" : "rgba(0,80,0,$percent)";
}

?>
<table>
    <tr>
        <td style="width:100px;">涨跌幅区间</td>
        <td style="width:300px;"></td>
        <td style="width:60px;">数量</td>
        <td style="width:80px;">百分比</td>
    </tr>

    <?
    $zhang = 0;
    $die   = 0;
    foreach ($analyse_result as $k => $v) {
        $percent      = Number::getPercent($v, $total);
        $widthPercent = Number::getPercent($v, $max);
        $divClass     = $k < 0 ? 'green' : 'red';

        $ampColor = getColor($k);

        $str_per_left  = $k == -10 ? '...' : ($k + 1) . '%';
        $str_per_right = $k == 9 ? '...' : ($k + 1) . '%';

        if ($k < 0) {
            $die += $v;
        }
        else {
            $zhang += $v;
        }

        ?>
        <tr class="<?= $divClass ?>">
            <td style="background-color: <?= $ampColor ?>"><?= $str_per_left ?>-<?= $str_per_right ?></td>
            <td>
                <div style="width: <?= $widthPercent ?>%;height: 8px;"></div>
            </td>
            <td><?= $v ?></td>
            <td><?= $percent ?>%</td>
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
</style>
