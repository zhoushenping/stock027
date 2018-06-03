<?
//筛选最近三个工作日跌幅达到15%以上的，并且市盈率小于50的股票池
$ampB_r = $_REQUEST['ampB'];
if ($ampB_r == 0) {
    $ampB_r = 3;
}
$ampC_r = $_REQUEST['ampC'];
if ($ampC_r == 0) {
    $ampC_r = 0;
}

$tradeDates = TradeDate::getTradeDates();
$weekDay    = date('w');

if ($weekDay == 0 || $weekDay == 6) {
    $date_today = $tradeDates[0];
}
else {
    $date_today = date('Y-m-d');
}

$t1 = strtotime("$date_today 09:30:00");
$t2 = strtotime("$date_today 17:30:00");

$dateB_r = $_REQUEST['dateB'];
$dateC_r = $_REQUEST['dateC'];

if (empty($dateB_r)) {
    if (time() < $t1) {
        $dateB_r = $tradeDates[3];
        $dateC_r = $tradeDates[5];
    }
    else if (time() >= $t1 && time() <= $t2) {
        $dateB_r = $tradeDates[2];
        $dateC_r = $tradeDates[4];
    }
    else {
        $dateB_r = $tradeDates[3];
        $dateC_r = $tradeDates[5];
    }
}
$priceListB = eastDailySummary::getDayLastPriceList($dateB_r);
$priceListC = eastDailySummary::getDayLastPriceList($dateC_r);

if (time() < $t1) {
    $priceListA = eastDailySummary::getDayLastPriceList($tradeDates[0]);
}
else if (time() >= $t1 && time() <= $t2) {
    $priceListA = eastLast::getLastPriceList();
}
else {
    $priceListA = eastDailySummary::getDayLastPriceList($date_today);
}

foreach ($priceListA as $symbol => $priceA) {
    $priceB = $priceListB[$symbol];
    $priceC = $priceListC[$symbol];

    if ($priceB == 0 || $priceC == 0) continue;

    if (abs(($priceA - $priceB) / $priceB * 100) > $ampB_r
        && abs(($priceA - $priceC) / $priceC * 100) > $ampC_r
    ) {
        $ampList3[$symbol] = Number::getDiffRate($priceA, $priceB);
        $ampList5[$symbol] = Number::getDiffRate($priceA, $priceC);
    }
}

$records = [];
foreach (DBHandle::select(eastLast::table) as $item) {
    if ($item['syl'] <= 0 || $item['syl'] >= 50) continue;

    if (isset($ampList3[$item['symbol']])) {
        $item['amp3'] = $ampList3[$item['symbol']];
        $item['amp5'] = $ampList5[$item['symbol']];
        $records[]    = $item;
    }
}

function cmp($a, $b)
{
    $amp1 = $a['marketValue'];
    $amp2 = $b['marketValue'];

    if ($amp1 == $amp2) {
        return 0;
    }

    return ($amp1 < $amp2) ? -1 : 1;
}

usort($records, "cmp");

$records = array_reverse($records);
