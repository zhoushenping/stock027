<?
$uid       = '';
$uname     = '';
$uemail    = '';
$myOASInfo = ODP::getUserInfoFromOasLoginKey();

if ($myOASInfo['status'] == 'ok') {
    $uid     = $myOASInfo['uid'];
    $oas_uid = $myOASInfo['val']['id'];
    $uname   = !empty($myOASInfo['val']['nickname']) ? $myOASInfo['val']['nickname'] : $myOASInfo['val']['uname'];
    $uemail  = $myOASInfo['val']['email'];
}
