<?php
if ($_REQUEST['key'] != 'wyytdsn') die;

DingTalk::send($_REQUEST['phone'], $_REQUEST['msg']);
die;
