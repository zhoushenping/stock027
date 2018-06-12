<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/6/10
 * Time: 上午10:18
 */
$info = [];

foreach (eastZhishu::$names as $symbol => $name) {
    $stats = [];

    $rs = eastZhishu::get(substr($symbol, 0, -1));
    $rs = array_reverse($rs);

    $amp_last4 = 0;
    foreach ($rs as $item) {
        if ($item['date'] < 20180108) continue;

        $w    = date('w', strtotime($item['date']));
        $week = date('W', strtotime($item['date']));
        $year = date('Y', strtotime($item['date']));

        if (in_array($w, [4, 5])) {
            $stats[$year . $week][$w] = $item['amp'];
        }
    }

    foreach ($stats as $item) {
        $info[$symbol][getLevel($item[4])][getLevel($item[5])]++;
    }
}
/*
function getLevel($amp)
{
    $abs = abs($amp);

    if ($abs <= 0.5) return 'zhendang';

    $fudu = 'xiao';
    if ($abs > 0.5 && $abs <= 1) $fudu = 'xiao';
    if ($abs > 1 && $abs <= 1.5) $fudu = 'zhong';
    if ($abs > 1.5) $fudu = 'da';

    return $fudu . ($amp > 0 ? 'zhang' : 'die');
}
*/

function getLevel($amp)
{

    return ($amp > 0 ? 'zhang' : 'die');
}

$str = [
    'dadie'      => '大跌',
    'zhongdie'   => '中跌',
    'xiaodie'    => '小跌',
    'zhendang'   => '震荡',
    'xiaozhang'  => '小涨',
    'zhongzhang' => '中涨',
    'dazhang'    => '大涨',
];

$str = [
    'die' => '跌',
    'zhang' => '涨',
];

//var_dump($info);
?>
<?
foreach ($info as $symbol => $null) {
    ?>
    <table>
        <caption>
            <?= eastZhishu::$names[$symbol] ?>周四 - 周五趋势统计
        </caption>
        <tr>
            <td rowspan="2">周四</td>
            <td colspan="<?= count($str) ?>">周五</td>
        </tr>
        <tr>
            <?
            foreach ($str as $k => $name) {
                ?>
                <td><?= $name ?></td>
                <?
            }
            ?>
        </tr>
        <?
        foreach ($str as $k => $name) {
            ?>
            <tr>
                <td><?= $name ?></td>
                <?
                foreach ($str as $k2 => $name2) {
                    echo "
                <td>
                    {$info[$symbol][$k][$k2]}
                </td>
                ";
                }

                ?>
            </tr>
            <?
        }
        ?>
    </table>
    <?
}
?>
<style>
    table, td {
        border: 1px solid skyblue;
        border-collapse: collapse;;
    }
</style>


