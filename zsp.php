<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/9/20
 * Time: 下午11:54
 */
set_time_limit(300);
ini_set('memory_limit', '128M');

include_once(dirname(__FILE__) . '/define.php');
require_once(ROOT_PATH . 'include' . DS . 'config' . DS . 'global.php');

$info = [
    'sz002311' => ['have' => 2500, 'chengben' => 19.36, 'target' => 21.20],//海大集团
    'sz000666' => ['have' => 500, 'chengben' => 20.34, 'target' => 14.94],//经纬纺机
    'sz002354' => ['have' => 1, 'chengben' => 20.615, 'target' => 21.05],//天神娱乐
    'sh600518' => ['have' => 700, 'chengben' => 20.908, 'target' => 21.31],//康美药业
    'sz002223' => ['have' => 1, 'chengben' => 14.63, 'target' => 14.94],//鱼跃医疗
    'sh600660' => ['have' => 1, 'chengben' => 26.97, 'target' => 27.45],//福耀玻璃
    //    'sz002511' => ['have' => 1, 'chengben' => 14.63, 'target' => 14.94],//中顺洁柔
    //    'sz002008' => ['have' => 1, 'chengben' => 47.892, 'target' => 48.20],//大族激光
    //    'sh600198' => ['have' => 7500, 'chengben' => 13.912, 'target' => 14.20],//大唐电信
    //    'sz002511' => ['have' => 1700, 'chengben' => 14.63, 'target' => 14.94],//中顺洁柔
    //    'sh600031' => ['have' => 1700, 'chengben' => 7.943, 'target' => 14.94],//三一重工
    //    'sh600398' => ['have' => 1400, 'chengben' => 9.624, 'target' => 27.45],//海澜之家
];

$columns_show = [
    'name',
    //    'open',
    //    'settlement',
    'trade',
    //    'high',
    //    'low',
    //    'volume',
    //    'amount',
    //    'b1c',
    //    'b1p',
    //    's1c',
    //    's1p',
    'symbol',
];

foreach ($info as $symbol => $null) {
    $symbols[] = $symbol;
}

$rs = RealTime::get($symbols);

$exchange = [
    'volume' => '成交量（万手）',
    'amount' => '成交额（亿元）',
];

function getExchangeStr($key, $str)
{
    global $exchange;
    if (isset($exchange[$key])) return $exchange[$key];

    return $str;
}

function getExchangeNum($key, $num)
{
    $config = [
        'volume' => 10000,
        'amount' => 10000 * 10000,
    ];

    if (!isset($config[$key])) return $num;

    return number_format($num / $config[$key], 2, '.', '');
}

$salary = [];

?>
<table>
    <tr style="/*display: none;*/">
        <?
        foreach (RealTime::$arr_columns as $k => $v) {
            if (!in_array($k, $columns_show)) continue;
            $v = getExchangeStr($k, $v);
            echo "<th>$v</th>";
        }
        ?>
        <th>涨幅</th>
        <!--        <th>持仓量</th>-->
        <!--        <th>持仓成本</th>-->
        <!--        <th>今日盈亏</th>-->
        <th>持仓盈亏率</th>
        <!--        <th>目标价</th>-->
        <th>目标差</th>
    </tr>

    <?
    foreach ($rs as $item) {
        $html = '';
        foreach ($item as $k => $v) {
            if (!in_array($k, $columns_show)) continue;
            $v = getExchangeNum($k, $v);
            $html .= "<td>$v</td>";
        }

        $amp = number_format(($item['trade'] / $item['settlement'] - 1) * 100, 2, '.', '') . '%';
        $html .= "<td>$amp</td>";

//        $html .= "<td>" . $info[$item['symbol']]['have'] . "</td>";
//        $html .= "<td>" . $info[$item['symbol']]['chengben'] . "</td>";

        $salary[$item['symbol']] =
            number_format($info[$item['symbol']]['have'] * ($item['trade'] - $item['settlement']), 2, '.', '');
//        $html .= "<td>{$salary[$item['symbol']]}</td>";

        $bonusRate = Number::getDiffRate($item['trade'], $info[$item['symbol']]['chengben']);
        $html .= "<td>{$bonusRate}%</td>";
//        $html .= "<td>{$info[$item['symbol']]['target']}</td>";
        $html .= "<td>" . Number::getFloat($item['trade'] - $info[$item['symbol']]['target'], 2) . "</td>";

        echo "<tr>$html</tr>";
        Log2::save_run_log("{$item['name']},现价={$item['trade']},涨幅=$amp,今日盈利={$salary[$item['symbol']]}", 'aaa');
    }
    Log2::save_run_log("", 'aaa');
    ?>
    <tr>
        <td colspan="<?= count($columns_show) + 3 ?>">
            <?= array_sum($salary) ?>
        </td>
    </tr>
</table>
<style>
    td, th {
        border: 1px solid transparent;
        padding: 3px 5px;
        color: black;
        font-size: 12px;
    }

    table, td {
        border-collapse: collapse;
    }

    table {
        opacity: 0.6;
        position: fixed;
    }
</style>
<script src="/static/common/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
    $(function () {
        setInterval("window.location.reload();", 5000);
    })
</script>

