<?php
$count = eastHistory::downloadMuti();

if ($count == 0) Browser::headerRedirect("./?a=east&m=login");
echo "got $count records";
