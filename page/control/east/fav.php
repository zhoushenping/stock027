<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/12/10
 * Time: 下午8:47
 */

if ($_REQUEST['func'] == 'add') {
    Fav::add(1, $_REQUEST['symbol']);
    Browser::callback(
        [
            'status' => 'ok',
        ]
    );
}
if ($_REQUEST['priceType'] != '') {
    Fav::updatePrice(1, $_REQUEST['symbol'], $_REQUEST['priceType'], $_REQUEST['price']);
    Browser::callback(
        [
            'status' => 'ok',
        ]
    );
}

$allInfo = StockList::readAllRecord();

$info      = [];
$stockList = [];
$uid       = 1;

$stockInfo = eastStockInfo::readAllRecord();
$lastInfo  = eastLast::getLastInfo();

foreach (Fav::getUserFav($uid) as $item) {
    $item = array_merge($item, $lastInfo[$item['symbol']]);

    $item['price']     = $item['trade'];
    $item['diff_buy']  = $item['thresh_buy'] > 0 ? Number::getDiffRate($item['thresh_buy'], $item['price'], 1) : '-';
    $item['diff_sell'] = Number::getDiffRate($item['price'], $item['thresh_sell'], 1);
    $info[]            = $item;
}

foreach ($stockInfo as $item) {
    $stockList[] = [
        'symbol' => $item['symbol'],
        'pinyin' => $item['pinyin'],
        'name'   => $allInfo[$item['symbol']]['name'],
    ];
}

function pailie($a, $b)
{
    if ($a['amp'] == $b['amp']) return 0;

    return ($a['amp'] > $b['amp']) ? -1 : 1;
}

usort($info, 'pailie');
