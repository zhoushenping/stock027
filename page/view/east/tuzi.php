<script src="./static/common/jquery-1.7.2.min.js"></script>
<script src="./static/common/tools.php"></script>
<link href="./static/common/sort/jq.css" rel="stylesheet">
<link href="./static/common/sort/theme.default.min.css" rel="stylesheet">
<script src="./static/common/sort/jquery.tablesorter.min.js"></script>
<script src="./static/common/sort/jquery.tablesorter.widgets.min.js"></script>
<script src="./static/page/east/tuzi.js?3306"></script>
<link rel="stylesheet" href="./static/page/east/tuzi.css?21">
<style>
    .blue {
        color: blue;
    }

    .red {
        color: red;
    }

    .purple {
        color: purple;
    }

    .deeppink {
        color: deeppink;
    }
</style>
<div style="width: 800px;margin:0px auto;">
    <table class="tablesorter">
        <caption>[<span class="red"><?= $date_s ?>-<?= $date_e ?></span>]兔子股票(<span
                class="red"><?= count($info) ?></span>)只
        </caption>
        <thead>
        <tr>
            <th class="sorter-false">股票代码</th>
            <th class="sorter-false">股票名称</th>
            <th class="number">市值(亿)</th>
            <th class="number">市盈率</th>
            <th class="number">现涨幅</th>
            <!--            <th class="sorter-false">前谷价</th>-->
            <!--            <th class="sorter-false">最高价</th>-->
            <!--            <th class="sorter-false">最高价日期</th>-->
            <!--            <th class="sorter-false">后谷价</th>-->
            <th class="number">最大上坡幅度</th>
            <th class="number">最大下坡幅度</th>
            <!--            <th class="sorter-false">现价</th>-->
            <th class="number">现价下坡幅度</th>
            <th class="number">盈利空间</th>
            <!--            <th class="sorter-false">操作</th>-->
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
                <td>
                    <button class="viewChart"><?= $item['name'] ?></button>
                </td>
                <td><?= Number::getFloat($item['marketValue'], 0) ?>亿</td>
                <td><?= Number::getFloat($item['syl'], 1) ?></td>
                <td class="<?= $item['amp'] > 0 ? 'red' : 'green' ?>"><?= Number::getFloat($item['amp'], 1) ?>%</td>
                <!--                <td>--><?//= $item['low1'] ?><!--</td>-->
                <!--                <td class="blue">--><?//= $item['top'] ?><!--</td>-->
                <!--                <td class="blue">--><?//= $item['topDate'] ?><!--</td>-->
                <!--                <td class="red">--><?//= $item['low2'] ?><!--</td>-->
                <td class="red"><?= $item['shangpo_max'] ?>%</td>
                <td class="red"><?= $item['xiapo_max'] ?>%</td>
                <!--                <td class="deeppink">--><?//= $item['trade'] ?><!--</td>-->
                <td class="deeppink"><?= $item['xiapo_now'] ?>%</td>
                <td class="purple">
                    <button class="viewChart"><?= $item['bonusSpace'] ?>%</button>
                </td>
                <!--
                <td>
                    <button class="viewChart">查看K线图</button>
                </td>
                -->
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
</div>

