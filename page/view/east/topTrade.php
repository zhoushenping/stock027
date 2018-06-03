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
            $(this).parent().parent().after("<tr class='chartContainer'><td colspan='9'></td></tr>");
            $('.chartContainer td').append('<iframe src="./null.html" width="570" height="500" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe>');
            $('.chartContainer iframe').attr('src', 'http://118.190.148.110/zsp/?a=sina&m=clear_chart&type=simple&symbol=' + $(this).parent().parent().find('td:eq(1)').text());
        })

        $('.chartContainer td').live('click', function () {
            $(this).parent().remove();
        })
    })
</script>
<style>
    div.main, .queryInfo {
        margin: 50px auto;
        width: 800px;
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


    .shizhi {
        color: darkblue;
    }

    .syl {
        color: brown;
    }

    .jiaoyie {
        color: deepskyblue;
    }

    .zhangfu {
        font-weight: bold;
        color: red;
    }
</style>
<div class="main">
    <table class="tablesorter">
        <thead>
        <tr>
            <th class="sorter-false">排名</th>
            <th class="sorter-false">股票编号</th>
            <th class="sorter-false">股票名称</th>
            <th class="number">市值</th>
            <th class="number">市盈率</th>
            <th class="number">交易额</th>
            <th class="number">换手率</th>
            <th class="number">涨幅</th>
            <th class="sorter-false">操作</th>
        </tr>
        </thead>
        <tbody>

        <?
        foreach ($rs as $i => $item) {
            ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $item['symbol'] ?></td>
                <td><?= $item['name'] ?></td>
                <td class="shizhi"><?= Number::getFloat($item['marketValue'], 0) ?>亿</td>
                <td class="syl"><?= $item['syl'] ?></td>
                <td class="jiaoyie"><?= Number::getFloat(Number::getYi($item['amount']), 0) ?>亿</td>
                <td><?= Number::getPercent($item['amount'] / 10000 / 10000, $item['marketValue'], 1) ?>%</td>
                <td class="zhangfu"><?= Number::getFloat($item['amp'], 1) ?>%</td>
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

