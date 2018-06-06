<title>history</title>
<script src="./static/common/jquery-1.7.2.min.js"></script>
<script src="./static/common/tools.php"></script>
<style>
    td, th {
        border: 1px solid skyblue;
        padding: 2px 10px;
    }

    td, th, table {
        border-collapse: collapse;
    }

    table {
        margin: 10px auto 20px;
    }

    .symbol {
        color: deeppink;
    }
</style>
<?
$plus  = [];
$Zjfss = [];
foreach ($symbols as $symbol) {

    ?>
    <table>
        <caption><?= $names[$symbol] ?>[<span class="symbol"><?= $symbol ?></span>]</caption>
        <tr>
            <?
            foreach ($columns as $k) {
                echo "<th>" . eastHistory::$arr_columns[$k] . "</th>";
            }
            ?>
        </tr>
        <?
        foreach ($rs as $item) {
            if ($item['Zqdm'] != $symbol) continue;
            if ($item['time'] < $t0 || $item['time'] > $t1) continue;
            $Zjfss[$symbol][] = $item['Zjfss'];
            $td               = '';
            foreach ($columns as $k) {
                $v     = $item[$k];
                $title = '';
                if ($k == 'Cjsj') $v = date('m-d H:i', $item['time']);
                if ($k == 'Sxf') {
                    $v     = $item['Sxf'] + $item['Yhs'] + $item['Ghf'];
                    $title = "手续=" . (float)$item['Sxf'];

                    if ($item['Yhs'] > 0) {
                        $title .= ";印花税=" . (float)$item['Yhs'];
                    }
                    if ($item['Ghf'] > 0) {
                        $title .= ";过户费=" . (float)$item['Ghf'];
                    }
                }
                if (is_numeric($v) && $k != 'Zqdm') $v = (float)$v;

                if ($k == 'Cjsl' && $item['Mmlb_ex'] == 'S') $v = $v * -1;//卖出操作时成交数量显示为负值
                if ($k == 'Cjje' && $item['Mmlb_ex'] == 'B') $v = $v * -1;//买入操作时成交金额显示为负值

                if ($k == 'Sxf') $v = $v * -1;//手续费永远为负值
                $td .= "<td title='$title'>$v</td>";
            }

            echo "<tr>$td</tr>";
        }
        ?>
        <?
        $v0 = eastSalary::getChicang($uid, $t0, $symbol);
        $v1 = eastSalary::getChicang($uid, $t1 + 1, $symbol);

        $p0 = eastSalary::getPrice($symbol, $t0);
        $p1 = eastSalary::getPrice($symbol, $t1 + 1);

        $yue_change    = Number::getFloat(array_sum($Zjfss[$symbol]), 2);
        $plus[$symbol] = Number::getFloat($v1 * $p1 - $v0 * $p0 + $yue_change, 2);
        ?>
        <tr>
            <td colspan="<?= count($columns) ?>"><?= date('Y-m-d H:i:s', $t0) ?>前持有此股票
                <?= $v0 ?>股(时价<?= $p0 ?>),市值<?= $v0 * $p0 ?>元
            </td>
        </tr>
        <tr>
            <td colspan="<?= count($columns) ?>"><?= date('Y-m-d H:i:s', $t1) ?>后持有此股票
                <?= $v1 ?>股(时价<?= $p1 ?>),市值<?= $v1 * $p1 ?>元
            </td>
        </tr>
        <tr>
            <td colspan="<?= count($columns) ?>">
                期间可用余额变动合计=<?= $yue_change ?>;
                盈亏=<?= $plus[$symbol] ?>
            </td>
        </tr>
        <tr>
            <td colspan="<?= count($columns) ?>">
                优先级:
                <input type="text" maxlength="5" class="priority_r" value="<?= (int)$priority[$symbol] ?>"/>
                <button type="button" onclick="setPriority('<?= $symbol ?>',$(this).siblings('.priority_r').val());">修订
                </button>
            </td>
        </tr>
    </table>
    <?
}
?>

总仓盈亏=<?= Number::getFloat(array_sum($plus), 2) ?>

<script>
    function setPriority(symb, val) {
        var url = "./?a=eastAjax&m=priority&symbol=" + symb + "&val=" + val;
        var call = function (res) {

        };
        ajaxRequest2(url, call);
    }
</script>
