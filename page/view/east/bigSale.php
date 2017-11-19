<!-- Demo styling -->
<link href="https://mottie.github.io/tablesorter/docs/css/jq.css" rel="stylesheet">

<!-- jQuery: required (tablesorter works with jQuery 1.2.3+) -->
<script src="https://mottie.github.io/tablesorter/docs/js/jquery-1.2.6.min.js"></script>

<!-- Pick a theme, load the plugin & initialize plugin -->
<link href="https://mottie.github.io/tablesorter/dist/css/theme.default.min.css" rel="stylesheet">
<script src="https://mottie.github.io/tablesorter/dist/js/jquery.tablesorter.min.js"></script>
<script src="https://mottie.github.io/tablesorter/dist/js/jquery.tablesorter.widgets.min.js"></script>
<script>
    $(function () {
        $('table.tablesorter').tablesorter({
            widgets: ['zebra', 'columns'],
            usNumberFormat: false,
            sortReset: true,
            sortRestart: true
        });
    });
</script>

<style>
    div.main, .queryInfo {
        margin: 50px auto;
        width: 800px;
    }

    table, td {
        border-collapse: collapse;
    }

    td, th {
        border: 1px solid deepskyblue;
        padding: 5px 10px;
        font: 14px/22px Arial, Sans-serif;
    }

    span {
        color: deeppink;
    }

    tr.even {
        background-color: #eee;
    }

    tr:hover {
        background-color: lightcyan;
    }
</style>
<table class="queryInfo">
    <tr>
        <td colspan="9">
            过滤条件:
            市值>=<span><?= $threshold['marketValue'] ?></span>亿
            且 市盈率<=<span><?= $threshold['syl'] ?></span>
            且<?= $threshold['amp'] >= 0 ? '涨' : '跌' ?>幅不低于
            <span><?= abs($threshold['amp']) ?></span>%
        </td>
    </tr>
</table>

<div class="main">
    <table class="tablesorter">
        <thead>

        <tr>
            <th>股票名称</th>
            <th>股票代码</th>
            <th class="number">
                市值（亿）
            </th>
            <th class="number">
                市盈率
            </th>
            <th class="number">
                涨跌幅
            </th>
            <th class="sorter-false">昨收</th>
            <th class="sorter-false">现价</th>
            <th class="sorter-false">价格时间</th>
            <th class="sorter-false">相关链接</th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($records as $i => $item) {
            $links[$i] = "http://finance.sina.com.cn/realstock/company/{$item['symbol']}/nc.shtml";
            ?>
            <tr class="dataTR <?= $i % 2 == 0 ? 'odd' : 'even' ?>" id="tr<?= $i ?>">
                <td>
                    <a href="<?= $links[$i] ?>" target="_blank">
                        <?= $item['name'] ?>
                    </a>
                </td>
                <td>
                    <?= $item['symbol'] ?>
                </td>
                <td><?= $item['marketValue'] ?></td>
                <td><?= $item['syl'] ?></td>
                <td><?= $item['amp'] ?>%</td>
                <td><?= $item['settlement'] ?></td>
                <td><?= $item['trade'] ?></td>
                <td><?= date('Y-m-d H:i:s', $item['timestamp']) ?></td>
                <td>
                    <a target="_blank"
                       href="http://emweb.securities.eastmoney.com/f10_v2/OperationsRequired.aspx?type=web&code=<?= $item['symbol'] ?>">操盘必读</a>
                </td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
</div>


<script>

</script>


