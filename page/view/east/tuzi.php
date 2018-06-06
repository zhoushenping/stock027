<script src="./static/common/jquery-1.7.2.min.js"></script>
<script src="./static/common/tools.php"></script>
<link href="./static/common/sort/jq.css" rel="stylesheet">
<link href="./static/common/sort/theme.default.min.css" rel="stylesheet">
<script src="./static/common/sort/jquery.tablesorter.min.js"></script>
<script src="./static/common/sort/jquery.tablesorter.widgets.min.js"></script>
<script src="./static/page/east/tuzi.js"></script>
<link rel="stylesheet" href="./static/page/east/tuzi.css">

<div style="width: 1200px;margin:50px auto;">
    <table class="tablesorter">
        <caption>兔子股票</caption>
        <thead>
        <tr>
            <th class="sorter-false">股票代码</th>
            <th class="sorter-false">股票名称</th>
            <th class="number">市值(亿)</th>
            <th class="number">市盈率</th>
            <th class="number">现涨幅</th>
            <th class="sorter-false">现价</th>
            <th class="sorter-false">最高价</th>
            <th class="sorter-false">最高价日期</th>
            <th class="sorter-false">前谷价</th>
            <th class="sorter-false">后谷价</th>
            <th class="number">最大回调比</th>
            <th class="sorter-false">操作</th>
        </tr>
        </thead>

        <tbody>
        <?
        foreach ($info as $item) {
            ?>
            <tr>
                <td>
                    <a href="http://finance.sina.com.cn/realstock/company/<?= $item['symbol'] ?>/nc.shtml"
                       target="_blank"><?= $item['symbol'] ?></a>
                </td>
                <td><?= $item['name'] ?></td>
                <td><?= Number::getFloat($item['marketValue'], 0) ?></td>
                <td><?= Number::getFloat($item['syl'], 1) ?></td>
                <td class="<?= $item['amp'] > 0 ? 'red' : 'green' ?>"><?= Number::getFloat($item['amp'], 1) ?>%</td>
                <td><?= $item['trade'] ?></td>
                <td><?= $item['top'] ?></td>
                <td><?= $item['topDate'] ?></td>
                <td><?= $item['low1'] ?></td>
                <td><?= $item['low2'] ?></td>
                <td><?= $item['huitiao'] ?>%</td>
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

