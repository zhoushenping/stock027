<title>east</title>
<?
if (ZhanmoUsers::isLogined()) {
    $total = eastMoney::getTotal();
    if ($total == 0) {
        Browser::headerRedirect("?a=east&m=login");
    }
    ?>
    <a target="_blank" href="?a=east&m=download_history">重新下载操作历史</a><br/>
    <a target="_blank" href="?a=east&m=queryHistory">查询操作历史</a><br/>
    <a target="_blank" href="?a=east&m=queryHistoryGrouped">查询操作历史(按股票分组)</a><br/>
    <a target="_blank" href="?a=east&m=chart&type=buyed">曾持股曲线图</a><br/>
    <a target="_blank" href="?a=east&m=revoke_list">撤单</a><br/>
    <a target="_blank" href="?a=east&m=position">持仓</a><br/>
    <a target="_blank" href="?a=east&m=fav">favorite</a><br/>
    <br/>
    <?
}
else {
    ?>
    <iframe src="./?a=zhanmo&m=login" frameborder="0" style="width:600px;height:300px;"></iframe>
    <?
}
?>
<br/>
<a target="_blank" href="?a=east&m=amp_fenbu">涨跌幅分布</a><br/>
<a target="_blank" href="http://118.190.148.110/zhanmo001/web/">WR统计</a><br/>
<a target="_blank" href="?a=east&m=topTrade">交易巨头</a><br/>
<a target="_blank" href="?a=east&m=bigAmp3">3日大跌股</a><br/>
<a target="_blank" href="?a=east&m=topCounter">每日涨停统计</a><br/>
<a target="_blank" href="?a=east&m=daban">打板搜索</a><br/>
<a target="_blank" href="?a=east&m=tuzi">抓兔子</a><br/>
