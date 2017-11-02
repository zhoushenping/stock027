<?php
$info = explode(',', $_REQUEST['revokeStr']);
eastRevoke::revoke($info);
