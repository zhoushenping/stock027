<title>position</title>
<link rel="stylesheet" href="./static/page/east/position_list.css">


<script src="./static/common/jquery-1.7.2.min.js"></script>
<script src="./static/common/tools.php"></script>
<link href="./static/common/sort/jq.css" rel="stylesheet">
<link href="./static/common/sort/theme.default.min.css" rel="stylesheet">
<script src="./static/common/sort/jquery.tablesorter.min.js"></script>
<script src="./static/common/sort/jquery.tablesorter.widgets.min.js"></script>
<script src="./static/page/east/position_list.js"></script>
<div class="v_con hktrade pagetradeb Financial-position">
    <div class="maincenter-box-tip">
        <p class="ui-tiptext ui-tiptext-message">
            <span class="newBusi_Tit">
                资金持仓(<?= date('Y-m-d H:i:s') ?>)
            </span>
            <button class="refresh" onclick="window.location.reload();" title="" deluminate_imagetype="png">刷新</button>
        </p>
    </div>

    <div id="assest_cont" style="margin-bottom:10px;">
        <table class="zichan" style="width:70%;">
            <tbody>
            <tr class="tb-tr-bot lh300">
                <td class="tb-tr-right pad-box">
                    <span>总盈亏</span>
                    <span class="padl10">
                        <?
                        $positionInfo['zyk'] = Number::getFloat($positionInfo['Zzc'] - eastMoney::getTotal(), 2);
                        ?>
                        <span class="green">
                            <?= $positionInfo['zyk'] ?>
                        </span>
                    </span>
                </td>
                <td class="pad-box">
                    <span>总市值</span>
                    <span class="padl10">
                        <span class="">
                            <span class="red"><?= $positionInfo['Zxsz'] ?></span>
                        </span>
                    </span>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="tb-tr-right lh200 pad-box">
                    <span>可用资金</span>
                    <span class="padl10">
                        <span class=""><?= $positionInfo['Kyzj'] ?></span>
                    </span>
                </td>
                <td class="tb-tr-right pad-box">
                    <span>持仓盈亏</span>
                    <span class="padl10">
                        <span class="<?= $positionInfo['Ljyk'] > 0 ? 'red' :
                            'green' ?>"><?= $positionInfo['Ljyk'] ?></span>
                    </span>
                </td>
                <td class="pad-box">
                    <span>资金余额</span>
                    <span class="padl10">
                        <span class=""></span>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="tb-tr-right lh200 pad-box">
                    <span>可取资金</span>
                    <span class="padl10">
                        <span class=""><?= $positionInfo['Kqzj'] ?></span>
                    </span>
                </td>
                <td class="tb-tr-right pad-box">
                    <span>当日参考盈亏</span>
                    <span class="padl10">
                        <span class="<?= $positionInfo['Drckyk'] > 0 ? 'red' :
                            'green' ?>"><?= $positionInfo['Drckyk'] ?></span>
                    </span>
                </td>
                <td class="pad-box">
                    <span>冻结资金</span>
                    <span class="padl10"><?= $positionInfo['Djzj'] ?></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="listtable">
        <table class="tablesorter">
            <thead>
            <tr>
                <th class="number">证券代码</th>
                <th class="sorter-false">证券名称</th>
                <th class="sorter-false">持仓数量</th>
                <th class="sorter-false">可用数量</th>
                <th class="sorter-false">成本价</th>
                <th class="sorter-false">当前价</th>
                <th class="number">最新市值</th>
                <th class="number">浮动盈亏</th>
                <th class="number">盈亏比例</th>
                <th class="number">当日盈亏</th>
                <th class="number">当日盈亏比例</th>
                <th class="sorter-false">操作</th>
            </tr>
            </thead>
            <tbody id="tabBody">
            <?
            foreach ($positionInfo['F303S'] as $item) {
                $item['Zqdm'] = StockList::getStandardSymbol($item['Zqdm']);
                $sum['Zqsl'] += $item['Zqsl'];
                $sum['Kysl'] += $item['Kysl'];
                $sum['Ljyk'] += $item['Ljyk'];
                $sum['chengben'] += $item['Cbjg'] * $item['Zqsl'];
                ?>
                <tr class="">
                    <td><a href="http://quote.eastmoney.com/search.html?stockcode=<?= $item['Zqdm'] ?>"
                           target="_blank"><?= $item['Zqdm'] ?></a>
                    </td>
                    <td>
                        <button class="viewChart"><?= $item['Zqmc'] ?></button>
                    </td>
                    <td><?= $item['Zqsl'] ?></td>
                    <td><?= $item['Kysl'] ?></td>
                    <td><?= $item['Cbjg'] ?></td>
                    <td><?= Number::getFloat($item['Zxjg'], 2) ?></td>
                    <td><?= Number::getFloat($item['Zxsz'], 0) ?></td>
                    <td class="<?= $item['Ljyk'] > 0 ? 'red' : 'green' ?>">
                        <?= Number::getFloat($item['Ljyk'], 0) ?></td>
                    <td class="<?= $item['Ykbl'] > 0 ? 'red' : 'green' ?>">
                        <?= Number::getFloat($item['Ykbl'] * 100, 1) ?>%
                    </td>
                    <td class="<?= $item['Drljyk'] > 0 ? 'red' : 'green' ?>"><?= (float)$item['Drljyk'] ?></td>
                    <td class="<?= $item['Drykbl'] > 0 ? 'red' : 'green' ?>"><?= number_format($item['Drykbl'] * 100, 1) ?>%</td>
                    <td class="w100">
                        <button class="btn btn_buy mr5" type="button"
                                onclick="javascript:window.open('https://jy.xzsec.com//Trade/Buy?code=<?= $item['Zqdm'] ?>&name=<?= $item['Zqmc'] ?>&moneytype=');">
                            买
                        </button>
                        <button class="btn btn_sale green" type="button"
                                onclick="javascript:window.open('https://jy.xzsec.com//Trade/Sale?code=<?= $item['Zqdm'] ?>&name=<?= $item['Zqmc'] ?>&moneytype=');">
                            卖
                        </button>
                    </td>
                </tr>
                <?
            }

            eastBonus::insertRecord('1002', $positionInfo['zyk'], $positionInfo);

            ?>
            <!--
            <tr class="red">
                <td class="black fb" colspan="2">合 计</td>
                <td><?= $sum['Zqsl'] ?></td>
                <td><?= $sum['Kysl'] ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?= $sum['Ljyk'] ?></td>
                <td><?= Number::getFloat(100 * $sum['Ljyk'] / $sum['chengben'], 2) ?></td>
                <td></td>
                <td></td>
            </tr>
            -->
            </tbody>
        </table>
    </div>
    <div class="confooter">
        <div class="pager fr"></div>
    </div>
</div>
<script>
    function refresh() {
        window.location.reload();
    }

    $(function () {
        var t = <?=Time::isTradeTime() ? 10 : 120?>;
        var intv = setTimeout('refresh();', t * 1000000);
    })
</script>
