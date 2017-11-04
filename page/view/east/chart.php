<title><?= $type == 'now' ? '现持股' : '曾持股' ?></title>
<link rel="stylesheet" href="/static/page/east/chart.css">
<script src="/static/common/jquery-1.7.2.min.js"></script>
<body>
<div id="main_mask">
    <div id="show">
        <iframe src="/null.html" width="570" height="630" marginheight="0" marginwidth="0" frameborder="0"
                scrolling="no"></iframe>
        <span></span>
    </div>
</div>
<?

$symbols_shown = [];
foreach (eastHistory::getRecords() as $item) {
    if ($type == 'now' && $item['Gfye'] == 0) {
        $symbols_shown[] = $item['Zqdm'];
    }

    if (in_array($item['Zqdm'], $symbols_shown)) continue;
    $symbols_shown[] = $item['Zqdm'];
    $symbol          = $item['Zqdm'];
    $names[$symbol]  = $item['Zqmc'];

    ?>
    <div class="chart_container" id="chart_container_<?= $symbol ?>">
        <div class="chart_mask"></div>
        <iframe class="small" src="" width="573" height="296"
                style="" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe>
        <span></span>
    </div>
    <?
}
?>
</body>
<script>
    var names = <?=json_encode($names)?>;
    var iframe_url_common = '<?=$iframe_url_common?>';
    var refresh_time = <?=$type == 'now' ? 30 : 60?>;
</script>
<script src="/static/page/east/chart.js"></script>
