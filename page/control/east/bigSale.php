<?
$records = eastBigSale::findLow();

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
