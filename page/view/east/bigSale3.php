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
<div style="margin: 0 auto;width:500px;height:100px;">
    <form action="./?a=east&m=bigSale3" method="post">
        市值:
        <input type="text" name='marketValue_low' value="<?= $marketValue_low_r ?>">-
        <input type="text" name='marketValue_high' value="<?= $marketValue_high_r ?>">亿<br/>
        市盈率:
        <input type="text" name='syl_low' value="<?= $syl_low_r ?>">-
        <input type="text" name='syl_high' value="<?= $syl_high_r ?>"><br/>

        涨跌幅:
        <input type="text" name='low_amp' value="<?= $amp_low_r ?>">%-
        <input type="text" name='high_amp' value="<?= $amp_high_r ?>">%<br/>
        <button>查询</button>
    </form>
</div>

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


