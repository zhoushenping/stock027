<!-- Demo styling -->
<link href="./static/common/sort/jq.css" rel="stylesheet">

<!-- jQuery: required (tablesorter works with jQuery 1.2.3+) -->
<script src="./static/common/jquery-1.7.2.min.js"></script>

<!-- Pick a theme, load the plugin & initialize plugin -->
<link href="./static/common/sort/theme.default.min.css" rel="stylesheet">
<script src="./static/common/sort/jquery.tablesorter.min.js"></script>
<script src="./static/common/sort/jquery.tablesorter.widgets.min.js"></script>
<script>
    $(function () {
        $('table.tablesorter').tablesorter({
            widgets: ['zebra', 'columns'],
            usNumberFormat: true,
            sortReset: true,
            sortRestart: true
        });

        $('.viewChart').click(function () {
            $('.chartContainer').remove();
            $(this).parent().parent().after("<tr class='chartContainer'><td colspan='12'></td></tr>");
            $('.chartContainer td').append('<iframe src="./null.html" width="570" height="500" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe>');
            $('.chartContainer iframe').attr('src', 'http://118.190.148.110/zsp/?a=sina&m=clear_chart&type=simple&symbol=' + $(this).parent().parent().find('td:eq(1)').text());
        });

        $('.chartContainer td').live('click', function () {
            $(this).parent().remove();
        });
    })
</script>
<style>
    div.main, .queryInfo {
        margin: 50px auto;
        width: 1000px;
    }

    table {
        margin: 50px auto 0;
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

    button {
        cursor: pointer;
    }

    .syl {
        color: deeppink;
    }

    .todayAmp {
        color: darkblue;
    }

    .date {
        width: 100px;
    }

    .amp {
        width: 30px;
    }
</style>
<div class="main">
    <form action="" method="post">
        当前时间:<?= date('Y-m-d H:i:s') ?><br/>
        &nbsp;&nbsp;&nbsp;&nbsp;相对<input class="date" type="text" name="dateB" value="<?= $dateB_r ?>"/>的收盘价,
        涨跌超过<input class="amp" type="text" name="ampB" placeholder="请输入整数" value="<?= $ampB_r ?>">%<br/>

        且相对<input class="date" type="text" name="dateC" value="<?= $dateC_r ?>"/>的收盘价,
        涨跌超过<input class="amp" type="text" name="ampC" placeholder="请输入整数" value="<?= $ampC_r ?>">%<br/>
        <br/>
        <button type="submit">查询</button>
    </form>
    <table class="tablesorter">
        <thead>
        <tr>
            <th class="sorter-false">排名</th>
            <th class="sorter-false">股票编号</th>
            <th class="sorter-false">股票名称</th>
            <th class="number">交易额(亿)</th>
            <th class="number">市值(亿)</th>
            <th class="number">市盈率</th>
            <th class="number">换手率</th>
            <th class="number">当前价格</th>
            <th class="number">今日涨跌</th>
            <th class="number">3日涨跌</th>
            <th class="number">5日涨跌</th>
            <th class="sorter-false">操作</th>
        </tr>
        </thead>
        <tbody>

        <?
        foreach ($records as $i => $item) {
            ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $item['symbol'] ?></td>
                <td>
                    <a href="http://finance.sina.com.cn/realstock/company/<?= $item['symbol'] ?>/nc.shtml"
                       target="_blank"><?= $item['name'] ?></a>
                </td>
                <td><?= Number::getFloat(Number::getYi($item['amount']), 1) ?></td>
                <td><?= Number::getFloat($item['marketValue'], 1) ?></td>
                <td>
                    <span class="syl">
                        <?= $item['syl'] ?>
                    </span>
                </td>
                <td><?= Number::getPercent($item['amount'] / 10000 / 10000, $item['marketValue'], 1) ?>%</td>
                <td><?= $item['trade'] ?></td>
                <td>
                    <span class="todayAmp">
                        <?= Number::getFloat($item['amp'], 1) ?>%
                    </span>
                </td>
                <td><?= $item['amp3'] ?>%</td>
                <td><?= $item['amp5'] ?>%</td>
                <td>
                    <button class="viewChart">查看K线图</button>
                </td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
</div>

