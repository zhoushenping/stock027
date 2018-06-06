<?php
if (ZhanmoUsers::isLogined() == false) die;
$positionInfo = eastPosition::getList();
$temp         = $positionInfo['F303S'];

function cmp($a, $b)
{
    $amp1 = $a['Zxsz'];
    $amp2 = $b['Zxsz'];

    if ($amp1 == $amp2) {
        return 0;
    }

    return ($amp1 < $amp2) ? 1 : -1;
}

usort($temp, "cmp");
$positionInfo['F303S'] = $temp;
