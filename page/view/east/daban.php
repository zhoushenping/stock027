<script src="./static/common/jquery-1.7.2.min.js"></script>
<script src="./static/common/tools.php"></script>
<link href="./static/common/sort/jq.css" rel="stylesheet">
<link href="./static/common/sort/theme.default.min.css" rel="stylesheet">
<script src="./static/common/sort/jquery.tablesorter.min.js"></script>
<script src="./static/common/sort/jquery.tablesorter.widgets.min.js"></script>
<script src="./static/page/east/index_daban.js"></script>
<link rel="stylesheet" href="./static/page/east/index_daban.css">

<div style="width: 800px;margin:50px auto;">
    <div style="float: left;">
        <form action="" method="post">

            今日涨幅不低于
            <input type="text" name='amp_last_r' class='float amp_last_r' value="<?= $amp_last_r ?>">%,<br/>且它在之前
            <input type="text" name='days_r' class='int days_r' value="<?= $days_r ?>">个交易日中涨幅不低于
            <input type="text" name='amp_limit' class='float' value="<?= $amp_limit ?>">%的天数不超过
            <input type="text" name='count_limit' class='int count_limit' value="<?= $count_limit ?>">的股票

            <button type="submit">搜索</button>
        </form>
    </div>

    <table class="tablesorter">
        <caption>打板股票列表</caption>
        <thead>
        <tr>
            <th class="sorter-false">股票代码</th>
            <th class="sorter-false">股票名称</th>
            <th class="number">市值(亿)</th>
            <th class="number">市盈率</th>
            <th class="sorter-false">现价</th>
            <th class="number">涨幅</th>
            <th class="number">之前大涨天数</th>
            <th class="sorter-false">操作</th>
        </tr>
        </thead>

        <tbody>
        <?
        foreach ($infoList as $item) {
            ?>
            <tr>
                <td>
                    <a href="http://finance.sina.com.cn/realstock/company/<?= $item['symbol'] ?>/nc.shtml"
                       target="_blank"><?= $item['symbol'] ?></a>
                </td>
                <td><?= $item['name'] ?></td>
                <td><?= Number::getFloat($item['marketValue'], 0) ?></td>
                <td><?= Number::getFloat($item['syl'], 1) ?></td>
                <td><?= $item['trade'] ?></td>
                <td class="<?= $item['amp'] > 0 ? 'red' : 'green' ?>"><?= Number::getFloat($item['amp'], 1) ?>%</td>
                <td><?= $item['c'] ?></td>
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
