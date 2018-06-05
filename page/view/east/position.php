<title>positionChart</title>
<link rel="stylesheet" href="./static/page/east/chart.css">
<script src="./static/common/jquery-1.7.2.min.js"></script>
<body>
<div id="main_mask">
    <div id="show">
        <iframe src="./null.html" width="570" height="630" marginheight="0" marginwidth="0" frameborder="0"
                scrolling="no"></iframe>
        <span></span>
    </div>
</div>
<?
$list1[] = [
    'Zqdm' => 'sz399001',
    'Zqmc' => '深证成指',
];
$list1[] = [
    'Zqdm' => 'sh000001',
    'Zqmc' => '上证指数',
];
foreach ($list1 as $item) {
    $symbol         = StockList::getStandardSymbol($item['Zqdm']);
    $names[$symbol] = $item['Zqmc'];
    ?>
    <div class="chart_container" id="chart_container_<?= $symbol ?>">
        <!--        <div class="chart_mask"></div>-->
        <iframe class="small" src="" width="573" height="296"
                style="" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe>
        <span></span>
    </div>
    <?
}

echo "<br/>";

$list   = $positionInfo['F303S'];
foreach ($list as $item) {
    $symbol         = StockList::getStandardSymbol($item['Zqdm']);
    $names[$symbol] = $item['Zqmc'];
    ?>
    <div class="chart_container" id="chart_container_<?= $symbol ?>">
        <!--        <div class="chart_mask"></div>-->
        <iframe class="small" src="" width="573" height="296"
                style="" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe>
        <span></span>
    </div>
    <?
}
?>
<iframe src="./?a=east&m=position_list" frameborder="0" style="width: 900px;height: 600px;border:2px solid lightblue;"></iframe>
</body>
<script>
    var names = <?=json_encode($names)?>;
    var iframe_url_common = '<?=$iframe_url_common?>';
    var refresh_time = <?=$type == 'now' ? 30 : 60?>;
</script>
<script src="./static/page/east/chart.js"></script>
