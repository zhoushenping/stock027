<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/5/24
 * Time: 下午5:57
 */

?>
<script src="./static/common/jquery-1.7.2.min.js"></script>
<style>
    table, td {
        border-collapse: collapse;
    }

    td, th {
        border: 1px solid skyblue;
        text-align: center;
        padding: 4px 8px;
    }

    table {
        margin: 7px auto;
    }

    table.sys {
        margin: 20px auto;
        width: 800px;
    }

    .sub2 {
        display: none;
    }

    td.clickable:hover {
        background: lightpink;
        cursor: pointer;
    }

    .close {
        position: absolute;
        cursor: pointer;
        right: 6px;
        top: 4px;
    }

    .close:hover {
        background: lightpink;
    }

    iframe {
        margin: 7px 0;
    }

    .sub2 table tr:hover {
        background: lightpink;
    }

    button {
        cursor: pointer;
    }
</style>
<table class="sys">
    <caption>每日涨停数统计</caption>
    <tr>
        <th>日期</th>
        <th>合计</th>
        <?
        for ($i = 1; $i < count($levelList); $i++) {
            echo "<th>市值<br/>" . getLevelStr($i) . "亿</th>";
        }
        ?>
    </tr>
    <?
    foreach ($statis as $date => $item) {
        ?>
        <tr>
            <td><?= $date ?></td>
            <td class="clickable" onclick="showSub2(<?= $date ?>,<?= 0 ?>);">
                <?= array_sum($item) ?>
            </td>

            <?
            for ($i = 1; $i < count($levelList); $i++) {
                ?>
                <td class="clickable" onclick="showSub2(<?= $date ?>,<?= $i ?>);">
                    <?= $item[$i] ?>
                </td>
                <?
            }
            ?>
        </tr>

        <tr class="sub2 <?= $date ?>">
            <td colspan="7">
                <?
                foreach ($levelList as $level => $null) {
                    $arr = $infoList[$date][$level];

                    usort($arr, 'pailie');

                    ?>
                    <div class="sub2 <?= $date ?>_<?= $level ?>" style="position: relative;">
                        <button class="close">关闭</button>
                        <table style="width: 95%;">
                            <caption><?= $date ?>涨停股<? if ($level != 0) echo "(" . getLevelStr($level) . "亿)"; ?>
                                (<b><?= count($arr) ?></b>只)
                            </caption>
                            <tr>
                                <th class="sorter-false">股票代码</th>
                                <th class="sorter-false">股票名称</th>
                                <th class="number">市值</th>
                                <th class="number">交易额</th>
                                <th class="number">当日涨幅</th>
                                <th class="number">最新日涨幅</th>
                                <th class="sorter-false">操作</th>
                            </tr>
                            <?
                            foreach ($arr as $item) {
                                $url_east = "http://quote.eastmoney.com/{$item['symbol']}.html";
                                $url_sina = "http://finance.sina.com.cn/realstock/company/{$item['symbol']}/nc.shtml";
                                ?>
                                <tr>
                                    <td>
                                        <a href='<?= $url_east ?>' target='_blank'><?= $item['symbol'] ?></a>
                                    </td>
                                    <td>
                                        <a href='<?= $url_sina ?>' target='_blank'><?= $item['name'] ?></a>
                                    </td>
                                    <td><?= $item['mv'] ?>亿</td>
                                    <td><?= Number::getWan($item['amount']) ?>亿</td>
                                    <td><?= $item['amp'] ?>%</td>
                                    <td><?= $item['amp_last'] ?>%</td>
                                    <td>
                                        <button class="viewChart" id="view_<?= $item['symbol'] ?>">查看K线图</button>
                                    </td>
                                </tr>
                                <?
                            }
                            ?>
                        </table>
                    </div>
                    <?
                }
                ?>
            </td>
        </tr>
        <?
    }
    ?>
</table>
<script>
    var chart_tpl = "http://118.190.148.110/zsp/?a=sina&m=clear_chart&type=simple&symbol=";

    function showSub2(date, level) {
        $('.sub2').hide();
        $('.' + date + '_' + level).show();
        $('tr.' + date).show();
    }

    $('.close').click(function () {
        $('.sub2').hide();
    });

    $(function () {
        $('.viewChart').click(function () {
            var symbol = $(this).attr('id').substr(5);

            var tr = $(this).parent().parent();

//            $('.chartContainer').remove();
            tr.after("<tr class='chartContainer'><td colspan='7'></td></tr>");
            tr.parent().children('.chartContainer').children('td').append('<iframe class="' + symbol + '" src="./null.html" width="570" height="500" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe>');
            $('iframe.' + symbol).attr('src', 'http://118.190.148.110/zsp/?a=sina&m=clear_chart&type=simple&symbol=' + symbol);
        });

        $('.chartContainer td').live('click', function () {
            $(this).parent().remove();
        });
    })
</script>
