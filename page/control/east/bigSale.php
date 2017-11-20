<?
$threshold = [];
if ($_REQUEST['marketValue']) {
    $threshold = [
        'syl'         => (float)($_REQUEST['syl']),
        'amp'         => (float)($_REQUEST['amp']),
        'marketValue' => (float)($_REQUEST['marketValue']),
    ];
}

$records = eastBigSale::findLow($threshold);

if (empty($threshold)) {
    $threshold = eastBigSale::$sampleThreshold;
}

function cmp($a, $b)
{
    $amp1 = $a['amp'];
    $amp2 = $b['amp'];

    if ($amp1 == $amp2) {
        return 0;
    }

    return ($amp1 < $amp2) ? -1 : 1;
}

usort($records, "cmp");

if ($_REQUEST['amp'] > 0) {
    $records = array_reverse($records);
}


