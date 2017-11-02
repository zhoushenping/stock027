<?
$Zjfss = [];
?>
<table>
    <tr>
        <?
        foreach ($columns as $k) {
            echo "<th>" . eastHistory::$arr_columns[$k] . "</th>";
        }
        ?>
    </tr>
    <?
    foreach ($rs as $item) {
        $Zjfss[] = $item['Zjfss'];
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
    <tr>
        <td colspan="<?= count($columns) ?>">金额变动合计:<?= Number::getFloat(array_sum($Zjfss), 2) ?></td>
    </tr>
</table>
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
