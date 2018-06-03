<script src="./static/common/jquery-1.7.2.min.js"></script>
<script src="./static/common/tools.php"></script>
<link href="./static/common/sort/jq.css" rel="stylesheet">
<link href="./static/common/sort/theme.default.min.css" rel="stylesheet">
<script src="./static/common/sort/jquery.tablesorter.min.js"></script>
<script src="./static/common/sort/jquery.tablesorter.widgets.min.js"></script>
<script src="./static/page/east/index_fav.js"></script>
<link rel="stylesheet" href="./static/page/east/index_fav.css">

<div style="width: 800px;margin:50px auto;">
    <div style="float: right;">
        搜索:<input id="search" type="text" value=""/>
        <ul>

        </ul>
    </div>

    <table class="tablesorter">
        <caption>我关注的股票</caption>
        <thead>
        <tr>
            <th class="sorter-false">股票代码</th>
            <th class="sorter-false">股票名称</th>
            <th class="sorter-false">现价</th>
            <th class="number">市值(亿)</th>
            <th class="number">市盈率</th>
            <th class="number">涨幅</th>
            <th class="sorter-false" width="80px;">拟买价</th>
            <th class="number">价差比</th>
            <th class="sorter-false" width="80px;">拟卖价</th>
            <th class="number">价差比</th>
            <th class="sorter-false">操作</th>
        </tr>
        </thead>

        <tbody>
        <?
        foreach ($info as $item) {
            $color_buy  = $item['thresh_buy'] > 0 ? number_format($item['diff_buy'] / 100 + 0.333, 1) : 0;
            $color_sell = $item['thresh_sell'] > 0 ? number_format(($item['diff_sell'] / 100 + 0.33) / 1.33, 1) : 0;
            ?>
            <tr>
                <td>
                    <a href="http://finance.sina.com.cn/realstock/company/<?= $item['symbol'] ?>/nc.shtml"
                       target="_blank"><?= $item['symbol'] ?></a>
                </td>
                <td><?= $item['name'] ?></td>
                <td><?= $item['price'] ?></td>
                <td><?= Number::getFloat($item['marketValue'], 0) ?></td>
                <td><?= Number::getFloat($item['syl'], 1) ?></td>
                <td class="<?= $item['amp'] > 0 ? 'red' : 'green' ?>"><?= Number::getFloat($item['amp'], 1) ?>%</td>
                <td>
                    <span>
                        <?= $item['thresh_buy'] ?>
                    </span>
                    <input type="text" value="<?= $item['thresh_buy'] ?>" class="thresh_buy">
                </td>
                <td style="background-color: rgba(255,0,0,<?= $color_buy ?>);"><?= $item['diff_buy'] ?>%
                <td>
                    <span>
                        <?= $item['thresh_sell'] ?>
                    </span>
                    <input type="text" value="<?= $item['thresh_sell'] ?>" class="thresh_sell">
                </td>
                <td style="background-color: rgba(255,0,0,<?= $color_sell ?>);"><?= $item['diff_sell'] ?>%
                </td>
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
<script>
    var stockList = <?=json_encode($stockList)?>;
    var _existSymbols = new Array();

    <?
    foreach($info as $item){
    ?>
    _existSymbols.push('<?=$item['symbol']?>');
    <?
    }
    ?>
</script>
