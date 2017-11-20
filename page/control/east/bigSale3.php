<?
$amp_r              = (float)($_REQUEST['amp']);
$marketValue_low_r  = (int)$_REQUEST['marketValue_low'];
$marketValue_high_r = (int)$_REQUEST['marketValue_high'];
$syl_low_r          = (int)$_REQUEST['syl_low'];
$syl_high_r         = (int)$_REQUEST['syl_high'];
$amp_low_r          = (int)$_REQUEST['low_amp'];
$amp_high_r         = (int)$_REQUEST['high_amp'];

if ($marketValue_high_r == 0) $marketValue_high_r = 30000;
if ($syl_high_r == 0) $syl_high_r = 50;
if ($amp_high_r == 0) $amp_high_r = $amp_low_r + 1;

$records = eastBigSale::readAll();

foreach ($records as $i => $item) {
    if ($item['marketValue'] < $marketValue_low_r) unset($records[$i]);;
    if ($item['marketValue'] > $marketValue_high_r) unset($records[$i]);;
    if ($item['syl'] < $syl_low_r) unset($records[$i]);
    if ($item['syl'] > $syl_high_r) unset($records[$i]);
    if ($item['amp'] < $amp_low_r) unset($records[$i]);
    if ($item['amp'] >= $amp_high_r) unset($records[$i]);
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
